<?php

namespace App\Http\Controllers;

use App\Models\Building;
use App\Models\Camera;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class MapController extends Controller
{
    public function index(): Response
    {
        $buildings = Building::with(['cameras' => function($query) {
            $query->select('id', 'building_id', 'room_id', 'name', 'status', 'latitude', 'longitude')
                  ->whereNotNull('latitude')
                  ->whereNotNull('longitude');
        }])
        ->whereNotNull('latitude')
        ->whereNotNull('longitude')
        ->get();

        $stats = [
            'online' => Camera::where('status', 'online')->count(),
            'offline' => Camera::where('status', 'offline')->count(),
            'maintenance' => Camera::where('status', 'maintenance')->count(),
        ];

        return Inertia::render('Maps', [
            'buildings' => $buildings,
            'stats' => $stats,
        ]);
    }

    public function getMapData(): \Illuminate\Http\JsonResponse
    {
        $buildings = Building::with(['cameras' => function($query) {
            $query->select('id', 'building_id', 'room_id', 'name', 'status', 'latitude', 'longitude')
                  ->whereNotNull('latitude')
                  ->whereNotNull('longitude');
        }])
        ->whereNotNull('latitude')
        ->whereNotNull('longitude')
        ->get();

        return response()->json([
            'buildings' => $buildings
        ]);
    }
}
