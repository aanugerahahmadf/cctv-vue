<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Building;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Spatie\SimpleExcel\SimpleExcelWriter;

class BuildingController extends Controller
{
    public function index(): Response
    {
        $buildings = Building::query()->select(['id','name','slug','latitude','longitude'])->latest('id')->paginate(20);
        return Inertia::render('Admin/Buildings/Index', [
            'buildings' => $buildings,
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Admin/Buildings/Edit', [ 'building' => null ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'description' => 'nullable|string',
        ]);
        if (empty($data['slug'])) { $data['slug'] = str($data['name'])->slug(); }
        Building::create($data);
        return to_route('admin.buildings.index');
    }

    public function edit(Building $building): Response
    {
        return Inertia::render('Admin/Buildings/Edit', [ 'building' => $building ]);
    }

    public function update(Request $request, Building $building): RedirectResponse
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'description' => 'nullable|string',
        ]);
        if (empty($data['slug'])) { $data['slug'] = str($data['name'])->slug(); }
        $building->update($data);
        return to_route('admin.buildings.index');
    }

    public function destroy(Building $building): RedirectResponse
    {
        $building->delete();
        return back();
    }

    public function export()
    {
        $path = storage_path('app/public/buildings.xlsx');
        $writer = SimpleExcelWriter::create($path);
        $writer->addHeader(['ID','Name','Slug','Latitude','Longitude']);
        Building::query()->orderBy('id')->chunk(500, function ($chunk) use ($writer) {
            foreach ($chunk as $b) {
                $writer->addRow([$b->id, $b->name, $b->slug, $b->latitude, $b->longitude]);
            }
        });
        $writer->close();
        return response()->download($path)->deleteFileAfterSend(true);
    }
}
