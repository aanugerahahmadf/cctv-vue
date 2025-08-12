<?php

namespace Database\Seeders;

use App\Models\Building;
use App\Models\Room;
use Illuminate\Database\Seeder;

class RoomSeeder extends Seeder
{
    public function run(): void
    {
        $buildings = Building::all();
        
        if ($buildings->isEmpty()) {
            return;
        }

        $roomTypes = [
            'LOBBY',
            'KANTOR',
            'RUANG MEETING',
            'RUANG KONTROL',
            'RUANG OPERATOR',
            'RUANG TEKNISI',
            'RUANG MAINTENANCE',
            'RUANG GENSET',
            'RUANG POMPA',
            'RUANG TANGKI',
            'RUANG PRODUKSI',
            'RUANG LABORATORIUM',
            'RUANG GUDANG',
            'RUANG PARKIR',
            'RUANG KEAMANAN',
            'RUANG KANTIN',
            'RUANG MUSHOLA',
            'RUANG KESEHATAN',
            'RUANG TRAINING',
            'RUANG SERVER',
        ];

        foreach ($buildings as $building) {
            // Generate 5-15 rooms per building
            $roomCount = rand(5, 15);
            
            for ($i = 1; $i <= $roomCount; $i++) {
                $roomType = $roomTypes[array_rand($roomTypes)];
                $floor = rand(1, 5);
                
                Room::query()->firstOrCreate(
                    [
                        'building_id' => $building->id,
                        'name' => "{$roomType} {$i}"
                    ],
                    [
                        'floor' => "Lantai {$floor}",
                        'description' => "{$roomType} di {$building->name}",
                    ]
                );
            }
        }
    }
}
