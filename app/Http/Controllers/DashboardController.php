<?php

namespace App\Http\Controllers;

use App\Models\Building;
use App\Models\Room;
use App\Models\Camera;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    public function index(): Response
    {
        $stats = [
            'totalBuildings' => Building::count(),
            'totalRooms' => Room::count(),
            'onlineCameras' => Camera::where('status', 'online')->count(),
            'offlineCameras' => Camera::where('status', 'offline')->count(),
            'maintenanceCameras' => Camera::where('status', 'maintenance')->count(),
        ];

        return Inertia::render('Dashboard', [
            'stats' => $stats
        ]);
    }
}
