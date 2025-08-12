<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Building;
use App\Models\Camera;
use App\Models\User;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    public function __invoke(): Response
    {
        return Inertia::render('Admin/Dashboard', [
            'stats' => [
                'total_users' => User::count(),
                'total_buildings' => Building::count(),
                'total_cameras' => Camera::count(),
                'online' => Camera::where('status', 'online')->count(),
                'offline' => Camera::where('status', 'offline')->count(),
                'maintenance' => Camera::where('status', 'maintenance')->count(),
            ],
        ]);
    }
}
