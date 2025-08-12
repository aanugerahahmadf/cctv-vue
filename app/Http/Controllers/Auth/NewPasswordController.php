<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\VerificationCode;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Inertia\Response;

class NewPasswordController extends Controller
{
    /**
     * Display the password reset view.
     */
    public function create(Request $request): Response
    {
        // Legacy token-based view is not used in OTP flow, but keep for compatibility
        return Inertia::render('Auth/ResetPassword');
    }

    /**
     * Handle an incoming new password request via code.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => 'required|email',
            'code' => 'required|string',
            'password' => 'required|confirmed|min:8',
        ]);

        $record = VerificationCode::query()
            ->where('email', $request->string('email'))
            ->where('type', 'reset')
            ->where('code', $request->string('code'))
            ->orderByDesc('id')
            ->first();

        if (! $record || ! $record->isValidFor($request->string('email'), 'reset')) {
            throw ValidationException::withMessages([
                'code' => ['Kode tidak valid atau telah kedaluwarsa.'],
            ]);
        }

        $user = User::query()->where('email', $request->string('email'))->first();
        if (! $user) {
            throw ValidationException::withMessages([
                'email' => ['Email tidak ditemukan.'],
            ]);
        }

        $user->forceFill([
            'password' => Hash::make($request->string('password')),
        ])->save();

        $record->update(['used_at' => now()]);

        return redirect()->route('login')->with('status', 'Password berhasil direset. Silakan login.');
    }
}
