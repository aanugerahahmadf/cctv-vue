<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Building;
use App\Models\Camera;
use App\Models\User;
use App\Models\Room;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    public function __invoke(): Response
    {
        $totalUsers = User::count();
        $totalBuildings = Building::count();
        $totalRooms = Room::count();
        $totalCameras = Camera::count();
        
        $onlineCameras = Camera::where('status', 'online')->count();
        $offlineCameras = Camera::where('status', 'offline')->count();
        $maintenanceCameras = Camera::where('status', 'maintenance')->count();

        return Inertia::render('Admin/Dashboard', [
            'stats' => [
                'total_users' => $totalUsers,
                'total_buildings' => $totalBuildings,
                'total_rooms' => $totalRooms,
                'total_cameras' => $totalCameras,
                'online' => $onlineCameras,
                'offline' => $offlineCameras,
                'maintenance' => $maintenanceCameras,
                'system_status' => 'online',
                'uptime_percentage' => round(($onlineCameras / $totalCameras) * 100, 1),
            ],
        ]);
    }
}
