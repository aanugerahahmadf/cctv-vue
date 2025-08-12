<?php

namespace Database\Seeders;

use App\Models\Building;
use App\Models\Camera;
use App\Models\Room;
use Illuminate\Database\Seeder;

class CameraSeeder extends Seeder
{
    public function run(): void
    {
        $buildings = Building::with('rooms')->get();
        if ($buildings->isEmpty()) {
            return;
        }

        for ($i = 1; $i <= 700; $i++) {
            // IP suffix: 01..99 padded, then 100..700 plain
            $ipSuffix = $i < 100 ? str_pad((string)$i, 2, '0', STR_PAD_LEFT) : (string)$i;
            $ip = "10.56.236.$ipSuffix";
            $rtsp = "rtsp://admin:password.123@$ip/streaming/channels/101";

            // Random building and optional room in that building
            $building = $buildings->random();
            $room = $building->rooms->random() ?? null;

            // Randomize status (approx: 60% online, 30% offline, 10% maintenance)
            $rand = mt_rand(1, 100);
            $status = $rand <= 60 ? 'online' : ($rand <= 90 ? 'offline' : 'maintenance');

            Camera::query()->updateOrCreate(
                ['ip_address' => $ip],
                [
                    'building_id' => $building->id,
                    'room_id' => $room?->id,
                    'name' => "Camera $ipSuffix",
                    'rtsp_url' => $rtsp,
                    'status' => $status,
                    'latitude' => $building->latitude,
                    'longitude' => $building->longitude,
                    'is_public' => true,
                ]
            );
        }
    }
}
