<?php

namespace Database\Seeders;

use App\Models\Building;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class BuildingSeeder extends Seeder
{
    public function run(): void
    {
        $buildings = [
            'GEDUNG KOLABORATIF',
            'GERBANG UTAMA',
            'AWI',
            'SHELTER MAINTENANCE AREA 1',
            'SHELTER MAINTENANCE AREA 2',
            'SHELTER MAINTENANCE AREA 3',
            'SHELTER MAUNTENANCE AREA 4',
            'SHELTER WHITE OM',
            'PINTU MASUK AREA KILANG PERTAMINA',
            'MARINE REGION III PERTAMINA BALONGAN',
            'MAIN CONTROL ROOM',
            'TANK FARM AREA 1',
            'GEDUNG EXOR',
            'AREA PRODUKSI CRUDE DISTILLATION UNIT (CDU)',
            'HSSE DEMO ROOM',
            'GEDUNG AMANAH',
            'POC',
            'JGC',
        ];

        // Approximate coordinates near Pertamina RU VI Balongan
        $baseLat = -6.3640;
        $baseLng = 108.3840;

        foreach ($buildings as $index => $name) {
            Building::query()->firstOrCreate(
                ['name' => $name],
                [
                    'slug' => Str::slug($name),
                    'description' => null,
                    'latitude' => $baseLat + ($index * 0.001),
                    'longitude' => $baseLng + ($index * 0.001),
                ]
            );
        }
    }
}
