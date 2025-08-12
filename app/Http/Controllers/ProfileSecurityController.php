<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\VerificationCode;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class ProfileSecurityController extends Controller
{
    public function sendEmailChangeCode(Request $request): RedirectResponse
    {
        $request->validate([
            'new_email' => 'required|email',
        ]);

        // Send code to new email to verify ownership
        VerificationCode::createAndSend($request->string('new_email'), 'change_email', $request->user()->id);

        return back()->with('status', 'Kode verifikasi telah dikirim ke email baru.');
    }

    public function changeEmailWithCode(Request $request): RedirectResponse
    {
        $request->validate([
            'new_email' => 'required|email',
            'code' => 'required|string',
        ]);

        $record = VerificationCode::query()
            ->where('email', $request->string('new_email'))
            ->where('type', 'change_email')
            ->where('code', $request->string('code'))
            ->orderByDesc('id')
            ->first();

        if (! $record || ! $record->isValidFor($request->string('new_email'), 'change_email')) {
            throw ValidationException::withMessages([
                'code' => ['Kode tidak valid atau telah kedaluwarsa.'],
            ]);
        }

        $user = $request->user();
        $user->email = $request->string('new_email');
        $user->email_verified_at = null; // force re-verify
        $user->save();

        $record->update(['used_at' => now()]);

        return back()->with('status', 'Email berhasil diubah. Silakan verifikasi email baru Anda.');
    }

    public function sendPasswordChangeCode(Request $request): RedirectResponse
    {
        // Send code to current user email
        VerificationCode::createAndSend($request->user()->email, 'change_password', $request->user()->id);

        return back()->with('status', 'Kode verifikasi perubahan password telah dikirim ke email Anda.');
    }

    public function changePasswordWithCode(Request $request): RedirectResponse
    {
        $request->validate([
            'code' => 'required|string',
            'password' => 'required|confirmed|min:8',
        ]);

        $user = $request->user();

        $record = VerificationCode::query()
            ->where('email', $user->email)
            ->where('type', 'change_password')
            ->where('code', $request->string('code'))
            ->orderByDesc('id')
            ->first();

        if (! $record || ! $record->isValidFor($user->email, 'change_password')) {
            throw ValidationException::withMessages([
                'code' => ['Kode tidak valid atau telah kedaluwarsa.'],
            ]);
        }

        $user->forceFill([
            'password' => Hash::make($request->string('password')),
        ])->save();

        $record->update(['used_at' => now()]);

        return back()->with('status', 'Password berhasil diubah.');
    }
}