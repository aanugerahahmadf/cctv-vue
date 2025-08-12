<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\VerificationCodeMail;
use App\Models\EmailCode;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;

class CodeController extends Controller
{
    public function showRequest(): Response
    {
        return Inertia::render('Auth/RequestCode');
    }

    public function send(Request $request): RedirectResponse
    {
        $data = $request->validate(['email' => 'required|email', 'purpose' => 'required|string']);
        $user = User::where('email', $data['email'])->firstOrFail();
        $code = (string)random_int(100000, 999999);
        EmailCode::create([
            'user_id' => $user->id,
            'email' => $user->email,
            'purpose' => $data['purpose'],
            'code' => $code,
            'expires_at' => now()->addMinutes(10),
        ]);
        Mail::to($user->email)->send(new VerificationCodeMail($code, $data['purpose']));
        return back()->with('status', 'Kode verifikasi dikirim ke email.');
    }

    public function showVerify(): Response
    {
        return Inertia::render('Auth/VerifyCode');
    }

    public function verify(Request $request): RedirectResponse
    {
        $data = $request->validate(['email' => 'required|email', 'purpose' => 'required|string', 'code' => 'required|string']);
        $user = User::where('email', $data['email'])->firstOrFail();
        $record = EmailCode::where('user_id', $user->id)
            ->where('purpose', $data['purpose'])
            ->where('code', $data['code'])
            ->where('expires_at', '>', now())
            ->latest()->first();
        if (!$record) {
            return back()->withErrors(['code' => 'Kode tidak valid atau kadaluarsa']);
        }

        if ($data['purpose'] === 'reset_password') {
            $newPassword = Str::random(10);
            $user->update(['password' => Hash::make($newPassword)]);
            return back()->with('status', 'Password direset: '.$newPassword);
        }

        return back()->with('status', 'Kode terverifikasi.');
    }
}
