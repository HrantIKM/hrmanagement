<?php

namespace Database\Seeders\Position;

use App\Models\Department\Department;
use App\Models\Department\Enums\DepartmentCode;
use App\Models\Position\Position;
use Illuminate\Database\Seeder;

class PositionSeeder extends Seeder
{
    /**
     * Career ladder titles, created once per department.
     *
     * @return list<string>
     */
    private function titles(): array
    {
        return [
            'Intern',
            'Junior 1',
            'Junior 2',
            'Junior 3',
            'Middle 1',
            'Middle 2',
            'Middle 3',
            'Senior 1',
            'Senior 2',
            'Senior 3',
            'Team Lead',
            'Manager',
        ];
    }

    public function run(): void
    {
        $departments = Department::query()
            ->whereIn('name', DepartmentCode::values())
            ->orderBy('name')
            ->get();

        foreach ($departments as $department) {
            foreach ($this->titles() as $title) {
                Position::query()->updateOrCreate(
                    [
                        'title' => $title,
                        'department_id' => $department->id,
                    ],
                    [
                        'min_salary' => null,
                        'max_salary' => null,
                        'grade_level' => null,
                    ]
                );
            }
        }
    }
}
