<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\VerificationCode;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Inertia\Response;

class PasswordResetLinkController extends Controller
{
    /**
     * Display the password reset link request view.
     */
    public function create(): Response
    {
        return Inertia::render('Auth/ForgotPassword', [
            'status' => session('status'),
        ]);
    }

    /**
     * Handle an incoming password reset link request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $user = User::query()->where('email', $request->string('email'))->first();
        if (! $user) {
            throw ValidationException::withMessages([
                'email' => [__('We can\'t find a user with that e-mail address.')],
            ]);
        }

        VerificationCode::createAndSend($user->email, 'reset', $user->id);

        return redirect()->route('password.reset.code')->with('status', 'Kode reset password telah dikirim ke email Anda.');
    }
}
