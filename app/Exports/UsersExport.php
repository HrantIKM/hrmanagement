<?php

namespace App\Exports;

use App\Models\User\User;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class UsersExport implements FromQuery, WithHeadings, WithMapping
{
    public function query()
    {
        return User::query()->with(['department:id,name', 'position:id,title']);
    }

    /**
     * @return array<int, string>
     */
    public function headings(): array
    {
        return ['ID', 'Name', 'Email', 'Department', 'Position', 'Employment Status'];
    }

    /**
     * @param  User  $user
     * @return array<int, string|int|float|null>
     */
    public function map($user): array
    {
        return [
            $user->id,
            $user->name,
            $user->email,
            $user->department?->name,
            $user->position?->title,
            $user->employment_status,
        ];
    }
}
