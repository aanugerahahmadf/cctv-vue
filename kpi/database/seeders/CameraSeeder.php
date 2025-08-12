<?php

namespace Database\Seeders;

use App\Models\Building;
use App\Models\Camera;
use Illuminate\Database\Seeder;

class CameraSeeder extends Seeder
{
    public function run(): void
    {
        $buildings = Building::all();
        if ($buildings->isEmpty()) {
            return;
        }

        for ($i = 1; $i <= 700; $i++) {
            $ipSuffix = str_pad((string)$i, 3, '0', STR_PAD_LEFT);
            $ip = "10.56.236.$ipSuffix";
            $rtsp = "rtsp://admin:password.123@$ip/streaming/channels/";

            $building = $buildings[$i % $buildings->count()];

            Camera::query()->firstOrCreate(
                ['ip_address' => $ip],
                [
                    'building_id' => $building->id,
                    'room_id' => null,
                    'name' => "Camera $ipSuffix",
                    'rtsp_url' => $rtsp,
                    'status' => 'offline',
                    'latitude' => $building->latitude,
                    'longitude' => $building->longitude,
                    'is_public' => true,
                ]
            );
        }
    }
}
