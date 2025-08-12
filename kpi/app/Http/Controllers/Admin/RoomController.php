<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Building;
use App\Models\Room;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Spatie\SimpleExcel\SimpleExcelWriter;

class RoomController extends Controller
{
    public function index(): Response
    {
        $rooms = Room::query()->with('building:id,name')->select(['id','name','building_id','floor'])->latest('id')->paginate(20);
        return Inertia::render('Admin/Rooms/Index', [ 'rooms' => $rooms ]);
    }

    public function create(): Response
    {
        return Inertia::render('Admin/Rooms/Edit', [
            'room' => null,
            'buildings' => Building::select(['id','name'])->orderBy('name')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'building_id' => 'required|exists:buildings,id',
            'name' => 'required|string|max:255',
            'floor' => 'nullable|string|max:50',
            'description' => 'nullable|string',
        ]);
        Room::create($data);
        return to_route('admin.rooms.index');
    }

    public function edit(Room $room): Response
    {
        return Inertia::render('Admin/Rooms/Edit', [
            'room' => $room,
            'buildings' => Building::select(['id','name'])->orderBy('name')->get(),
        ]);
    }

    public function update(Request $request, Room $room): RedirectResponse
    {
        $data = $request->validate([
            'building_id' => 'required|exists:buildings,id',
            'name' => 'required|string|max:255',
            'floor' => 'nullable|string|max:50',
            'description' => 'nullable|string',
        ]);
        $room->update($data);
        return to_route('admin.rooms.index');
    }

    public function destroy(Room $room): RedirectResponse
    {
        $room->delete();
        return back();
    }

    public function export()
    {
        $path = storage_path('app/public/rooms.xlsx');
        $writer = SimpleExcelWriter::create($path);
        $writer->addHeader(['ID','Room','Building','Floor']);
        Room::with('building')->orderBy('id')->chunk(500, function ($chunk) use ($writer) {
            foreach ($chunk as $r) {
                $writer->addRow([$r->id, $r->name, optional($r->building)->name, $r->floor]);
            }
        });
        $writer->close();
        return response()->download($path)->deleteFileAfterSend(true);
    }
}
