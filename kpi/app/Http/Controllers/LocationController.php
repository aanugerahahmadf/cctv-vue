<?php

namespace App\Http\Controllers;

use App\Models\Building;
use App\Models\Room;
use Inertia\Inertia;
use Inertia\Response;

class LocationController extends Controller
{
    public function buildings(): Response
    {
        return Inertia::render('Locations/Buildings', [
            'buildings' => Building::select(['id','name','slug'])->orderBy('name')->get(),
        ]);
    }

    public function rooms(Building $building): Response
    {
        $building->load('rooms:id,building_id,name');
        return Inertia::render('Locations/Rooms', [
            'building' => $building,
            'rooms' => $building->rooms,
        ]);
    }

    public function cameras(Room $room): Response
    {
        $room->load(['building:id,name', 'cameras:id,name,room_id,status']);
        return Inertia::render('Locations/Cameras', [
            'room' => $room,
            'building' => $room->building,
            'cameras' => $room->cameras,
        ]);
    }
}
