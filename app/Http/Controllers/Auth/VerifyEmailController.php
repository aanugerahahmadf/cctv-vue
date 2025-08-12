<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\VerificationCode;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class VerifyEmailController extends Controller
{
    /**
     * Mark the authenticated user's email address as verified via signed link.
     */
    public function __invoke(EmailVerificationRequest $request): RedirectResponse
    {
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->intended(route('dashboard', absolute: false).'?verified=1');
        }

        if ($request->user()->markEmailAsVerified()) {
            event(new Verified($request->user()));
        }

        return redirect()->intended(route('dashboard', absolute: false).'?verified=1');
    }

    /**
     * Verify with OTP code for users not logged in yet.
     */
    public function verifyWithCode(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => 'required|email',
            'code' => 'required|string',
        ]);

        $record = VerificationCode::query()
            ->where('email', $request->string('email'))
            ->where('type', 'register')
            ->where('code', $request->string('code'))
            ->orderByDesc('id')
            ->first();

        if (! $record || ! $record->isValidFor($request->string('email'), 'register')) {
            return back()->withErrors(['code' => 'Kode tidak valid atau telah kedaluwarsa.']);
        }

        $user = \App\Models\User::query()->where('email', $request->string('email'))->first();
        if ($user && ! $user->hasVerifiedEmail()) {
            $user->markEmailAsVerified();
        }

        $record->update(['used_at' => now()]);

        return redirect()->route('login')->with('status', 'Email berhasil diverifikasi. Silakan login.');
    }
}
