<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Building;
use App\Models\Camera;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Spatie\SimpleExcel\SimpleExcelWriter;

class CameraController extends Controller
{
    public function index(): Response
    {
        $cameras = Camera::query()->with('building:id,name')->select(['id','name','ip_address','status','building_id'])->latest('id')->paginate(20);
        return Inertia::render('Admin/Cameras/Index', [
            'cameras' => $cameras,
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Admin/Cameras/Edit', [
            'camera' => null,
            'buildings' => Building::select(['id','name'])->orderBy('name')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'building_id' => 'required|exists:buildings,id',
            'name' => 'required|string|max:255',
            'ip_address' => 'required|ip|unique:cameras,ip_address',
            'rtsp_url' => 'required|string',
            'status' => 'required|in:online,offline,maintenance',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'is_public' => 'boolean',
        ]);
        Camera::create($data);
        return to_route('admin.cameras.index');
    }

    public function edit(Camera $camera): Response
    {
        return Inertia::render('Admin/Cameras/Edit', [
            'camera' => $camera,
            'buildings' => Building::select(['id','name'])->orderBy('name')->get(),
        ]);
    }

    public function update(Request $request, Camera $camera): RedirectResponse
    {
        $data = $request->validate([
            'building_id' => 'required|exists:buildings,id',
            'name' => 'required|string|max:255',
            'ip_address' => 'required|ip|unique:cameras,ip_address,'.$camera->id,
            'rtsp_url' => 'required|string',
            'status' => 'required|in:online,offline,maintenance',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'is_public' => 'boolean',
        ]);
        $camera->update($data);
        return to_route('admin.cameras.index');
    }

    public function destroy(Camera $camera): RedirectResponse
    {
        $camera->delete();
        return back();
    }

    public function export()
    {
        $path = storage_path('app/public/cameras.xlsx');
        $writer = SimpleExcelWriter::create($path);
        $writer->addHeader(['ID','Name','IP','Status','Building']);
        Camera::query()->with('building')->orderBy('id')->chunk(500, function ($chunk) use ($writer) {
            foreach ($chunk as $c) {
                $writer->addRow([$c->id, $c->name, $c->ip_address, $c->status, optional($c->building)->name]);
            }
        });
        $writer->close();
        return response()->download($path)->deleteFileAfterSend(true);
    }
}
