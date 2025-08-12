<?php

namespace App\Http\Controllers;

use App\Models\Building;
use App\Models\Camera;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class MapController extends Controller
{
    public function index(Request $request): Response
    {
        $status = $request->query('status'); // online|offline|maintenance|all|null

        $buildings = Building::query()
            ->with(['
                '])
            ->get(['id', 'name', 'slug', 'latitude', 'longitude']);

        $camerasQuery = Camera::query()->select(['id','name','building_id','room_id','latitude','longitude','status']);
        if ($status && $status !== 'all') {
            $camerasQuery->where('status', $status);
        }
        $cameras = $camerasQuery->get();

        return Inertia::render('Map', [
            'buildings' => $buildings,
            'cameras' => $cameras,
        ]);
    }
}
