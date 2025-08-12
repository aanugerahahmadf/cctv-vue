<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\Building;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Inertia\Inertia;
use Inertia\Response;

class RoomController extends Controller
{
    public function index(): Response
    {
        $rooms = Room::with(['building', 'cameras'])
            ->withCount('cameras')
            ->orderBy('name')
            ->get();

        return Inertia::render('Rooms/Index', [
            'rooms' => $rooms,
            'stats' => [
                'total' => $rooms->count(),
                'totalCameras' => $rooms->sum('cameras_count'),
            ]
        ]);
    }

    public function show(Building $building, Room $room)
    {
        $room->load(['cameras' => function($query) {
            $query->with(['building', 'room']);
        }]);
        
        return Inertia::render('Location/Room', [
            'building' => $building,
            'room' => $room,
            'cameras' => $room->cameras,
        ]);
    }

    public function create(): Response
    {
        $buildings = Building::orderBy('name')->get();

        return Inertia::render('Rooms/Create', [
            'buildings' => $buildings
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'building_id' => 'required|exists:buildings,id',
            'name' => 'required|string|max:255',
            'floor' => 'nullable|string|max:50',
            'description' => 'nullable|string',
        ]);

        // Check for unique room name within the same building
        $existingRoom = Room::where('building_id', $validated['building_id'])
            ->where('name', $validated['name'])
            ->first();

        if ($existingRoom) {
            return response()->json([
                'message' => 'Room name already exists in this building'
            ], 422);
        }

        $room = Room::create($validated);

        return response()->json([
            'message' => 'Room created successfully',
            'room' => $room->load('building')
        ]);
    }

    public function edit(Room $room): Response
    {
        $room->load('building');
        $buildings = Building::orderBy('name')->get();

        return Inertia::render('Rooms/Edit', [
            'room' => $room,
            'buildings' => $buildings
        ]);
    }

    public function update(Request $request, Room $room): JsonResponse
    {
        $validated = $request->validate([
            'building_id' => 'required|exists:buildings,id',
            'name' => 'required|string|max:255',
            'floor' => 'nullable|string|max:50',
            'description' => 'nullable|string',
        ]);

        // Check for unique room name within the same building (excluding current room)
        $existingRoom = Room::where('building_id', $validated['building_id'])
            ->where('name', $validated['name'])
            ->where('id', '!=', $room->id)
            ->first();

        if ($existingRoom) {
            return response()->json([
                'message' => 'Room name already exists in this building'
            ], 422);
        }

        $room->update($validated);

        return response()->json([
            'message' => 'Room updated successfully',
            'room' => $room->load('building')
        ]);
    }

    public function destroy(Room $room): JsonResponse
    {
        $room->delete();

        return response()->json([
            'message' => 'Room deleted successfully'
        ]);
    }

    public function getByBuilding(Building $building): JsonResponse
    {
        $rooms = $building->rooms()
            ->withCount('cameras')
            ->with(['cameras' => function($query) {
                $query->select('room_id', 'status');
            }])
            ->get();

        return response()->json([
            'rooms' => $rooms,
            'stats' => [
                'total' => $rooms->count(),
                'totalCameras' => $rooms->sum('cameras_count'),
            ]
        ]);
    }
}
