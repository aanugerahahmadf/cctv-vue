<?php

namespace App\Http\Controllers;

use App\Models\Building;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Inertia\Inertia;
use Inertia\Response;

class BuildingController extends Controller
{
    public function index(): Response
    {
        $buildings = Building::withCount(['rooms', 'cameras'])
            ->with(['cameras' => function($query) {
                $query->select('building_id', 'status');
            }])
            ->orderBy('name')
            ->get();

        return Inertia::render('Buildings/Index', [
            'buildings' => $buildings,
            'stats' => [
                'total' => $buildings->count(),
                'totalRooms' => $buildings->sum('rooms_count'),
                'totalCameras' => $buildings->sum('cameras_count'),
            ]
        ]);
    }

    public function show(Building $building)
    {
        $building->load(['rooms' => function($query) {
            $query->withCount(['cameras as online_cameras_count' => function($q) {
                $q->where('status', 'online');
            }, 'cameras as offline_cameras_count' => function($q) {
                $q->where('status', 'offline');
            }, 'cameras as maintenance_cameras_count' => function($q) {
                $q->where('status', 'maintenance');
            }]);
        }]);
        
        return Inertia::render('Location/Building', [
            'building' => $building,
            'rooms' => $building->rooms,
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Buildings/Create');
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:buildings,name',
            'slug' => 'required|string|max:255|unique:buildings,slug',
            'description' => 'nullable|string',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
        ]);

        $building = Building::create($validated);

        return response()->json([
            'message' => 'Building created successfully',
            'building' => $building
        ]);
    }

    public function edit(Building $building): Response
    {
        return Inertia::render('Buildings/Edit', [
            'building' => $building
        ]);
    }

    public function update(Request $request, Building $building): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:buildings,name,' . $building->id,
            'slug' => 'required|string|max:255|unique:buildings,slug,' . $building->id,
            'description' => 'nullable|string',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
        ]);

        $building->update($validated);

        return response()->json([
            'message' => 'Building updated successfully',
            'building' => $building
        ]);
    }

    public function destroy(Building $building): JsonResponse
    {
        $building->delete();

        return response()->json([
            'message' => 'Building deleted successfully'
        ]);
    }

    public function getMapData(): JsonResponse
    {
        $buildings = Building::with(['cameras' => function($query) {
            $query->select('building_id', 'status', 'latitude', 'longitude', 'name');
        }])
        ->whereNotNull('latitude')
        ->whereNotNull('longitude')
        ->get();

        return response()->json([
            'buildings' => $buildings
        ]);
    }

    public function search(Request $request): JsonResponse
    {
        $query = $request->get('q', '');
        
        if (strlen($query) < 2) {
            return response()->json(['buildings' => []]);
        }

        $buildings = Building::where('name', 'like', "%{$query}%")
            ->orWhere('description', 'like', "%{$query}%")
            ->limit(10)
            ->get(['id', 'name', 'description']);

        return response()->json([
            'buildings' => $buildings
        ]);
    }
}
