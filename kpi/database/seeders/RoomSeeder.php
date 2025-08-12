<?php

namespace Database\Seeders;

use App\Models\Building;
use App\Models\Room;
use Illuminate\Database\Seeder;

class RoomSeeder extends Seeder
{
    public function run(): void
    {
        $rooms = ['Lobi', 'Ruang Kontrol', 'Ruang Server', 'Kantor', 'Gudang'];
        foreach (Building::all() as $building) {
            foreach ($rooms as $r) {
                Room::firstOrCreate([
                    'building_id' => $building->id,
                    'name' => $r,
                ], [
                    'floor' => null,
                    'description' => null,
                ]);
            }
        }
    }
}
