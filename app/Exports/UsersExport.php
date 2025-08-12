<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class UsersExport implements FromQuery, WithHeadings, WithMapping
{
    public function query()
    {
        return User::query()->orderBy('id');
    }

    public function headings(): array
    {
        return ['ID', 'Name', 'Email', 'Verified At', 'Created At'];
    }

    public function map($u): array
    {
        return [
            $u->id,
            $u->name,
            $u->email,
            optional($u->email_verified_at)->toDateTimeString(),
            optional($u->created_at)->toDateTimeString(),
        ];
    }
}