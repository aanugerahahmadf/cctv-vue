<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Camera;
use App\Models\User;
use App\Models\Building;
use App\Models\Room;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\CamerasExport;
use App\Exports\UsersExport;

class ExportController extends Controller
{
    public function camerasCsv(): StreamedResponse
    {
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="cameras.csv"',
        ];

        $callback = function () {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['ID', 'Name', 'IP', 'Status', 'Building', 'Room', 'HLS URL']);

            Camera::with(['building', 'room'])->orderBy('id')->chunk(500, function ($cameras) use ($handle) {
                foreach ($cameras as $c) {
                    fputcsv($handle, [
                        $c->id,
                        $c->name,
                        $c->ip_address,
                        $c->status,
                        $c->building?->name,
                        $c->room?->name,
                        url($c->hls_url),
                    ]);
                }
            });

            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function usersCsv(): StreamedResponse
    {
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="users.csv"',
        ];

        $callback = function () {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['ID', 'Name', 'Email', 'Verified At', 'Created At']);

            User::orderBy('id')->chunk(500, function ($users) use ($handle) {
                foreach ($users as $u) {
                    fputcsv($handle, [
                        $u->id,
                        $u->name,
                        $u->email,
                        optional($u->email_verified_at)->toDateTimeString(),
                        optional($u->created_at)->toDateTimeString(),
                    ]);
                }
            });

            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function camerasXlsx()
    {
        return Excel::download(new CamerasExport, 'cameras.xlsx');
    }

    public function usersXlsx()
    {
        return Excel::download(new UsersExport, 'users.xlsx');
    }

    public function buildingsXlsx()
    {
        $data = Building::withCount(['rooms', 'cameras'])->orderBy('name')->get(['id','name']);
        return Excel::download(new class($data) implements \Maatwebsite\Excel\Concerns\FromArray, \Maatwebsite\Excel\Concerns\WithHeadings {
            public function __construct(private $rows) {}
            public function array(): array { return $this->rows->map(fn($b)=>[$b->id,$b->name,$b->rooms_count,$b->cameras_count])->toArray(); }
            public function headings(): array { return ['ID','Name','Rooms','Cameras']; }
        }, 'buildings.xlsx');
    }

    public function roomsXlsx()
    {
        $data = Room::with('building')->withCount('cameras')->orderBy('name')->get(['id','name','building_id']);
        return Excel::download(new class($data) implements \Maatwebsite\Excel\Concerns\FromArray, \Maatwebsite\Excel\Concerns\WithHeadings {
            public function __construct(private $rows) {}
            public function array(): array { return $this->rows->map(fn($r)=>[$r->id,$r->name,optional($r->building)->name,$r->cameras_count])->toArray(); }
            public function headings(): array { return ['ID','Name','Building','Cameras']; }
        }, 'rooms.xlsx');
    }
}
