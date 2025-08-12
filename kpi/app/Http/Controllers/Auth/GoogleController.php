<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;

class GoogleController extends Controller
{
    public function redirect(): RedirectResponse
    {
        return Socialite::driver('google')->redirect();
    }

    public function callback(): RedirectResponse
    {
        $googleUser = Socialite::driver('google')->stateless()->user();

        $user = User::query()->firstOrCreate(
            ['email' => $googleUser->getEmail()],
            [
                'name' => $googleUser->getName() ?: $googleUser->getNickname() ?: 'User',
                'password' => Hash::make(str()->random(32)),
                'email_verified_at' => now(),
            ]
        );

        // Ensure user role
        $role = Role::query()->where('name', Role::USER)->first();
        if ($role && !$user->hasRole(Role::USER)) {
            $user->roles()->attach($role->id);
        }

        Auth::login($user, remember: true);
        return redirect()->route('dashboard');
    }
}
