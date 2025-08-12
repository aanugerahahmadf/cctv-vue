<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
            AdminUserSeeder::class,
            BuildingSeeder::class,
            RoomSeeder::class,
            CameraSeeder::class,
            ContactSeeder::class,
        ]);
    }
}
