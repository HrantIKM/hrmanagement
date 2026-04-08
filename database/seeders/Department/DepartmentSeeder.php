<?php

namespace Database\Seeders\Department;

use App\Models\Department\Department;
use App\Models\Department\Enums\DepartmentCode;
use Illuminate\Database\Seeder;

class DepartmentSeeder extends Seeder
{
    public function run(): void
    {
        $descriptions = [
            DepartmentCode::IT->value => 'Engineering, infrastructure, and internal systems.',
            DepartmentCode::HR->value => 'People operations, talent, and workplace policies.',
            DepartmentCode::SALES->value => 'Revenue, accounts, and go-to-market execution.',
        ];

        foreach (DepartmentCode::cases() as $code) {
            Department::query()->updateOrCreate(
                ['name' => $code->value],
                ['description' => $descriptions[$code->value]]
            );
        }
    }
}
