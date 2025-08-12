<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::query()->firstOrCreate(
            ['email' => 'admin@pertamina.com'],
            [
                'name' => 'Administrator',
                'password' => Hash::make('admin123'),
                'email_verified_at' => now(),
            ]
        );

        $adminRole = Role::query()->where('name', Role::ADMIN)->first();
        if ($adminRole && !$admin->hasRole(Role::ADMIN)) {
            $admin->roles()->attach($adminRole->id);
        }
    }
}
