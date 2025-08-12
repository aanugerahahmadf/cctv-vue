<?php

namespace App\Exports;

use App\Models\Camera;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class CamerasExport implements FromQuery, WithHeadings, WithMapping
{
    public function query()
    {
        return Camera::query()->with(['building', 'room'])->orderBy('id');
    }

    public function headings(): array
    {
        return ['ID', 'Name', 'IP', 'Status', 'Building', 'Room', 'RTSP', 'HLS URL'];
    }

    public function map($c): array
    {
        return [
            $c->id,
            $c->name,
            $c->ip_address,
            $c->status,
            optional($c->building)->name,
            optional($c->room)->name,
            $c->rtsp_url,
            $c->hls_url ? url($c->hls_url) : null,
        ];
    }
}