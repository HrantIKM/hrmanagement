<?php

use App\Models\Department\Department;
use App\Models\Department\Enums\DepartmentCode;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $descriptions = [
            DepartmentCode::IT->value => 'Engineering, infrastructure, and internal systems.',
            DepartmentCode::HR->value => 'People operations, talent, and workplace policies.',
            DepartmentCode::SALES->value => 'Revenue, accounts, and go-to-market execution.',
        ];

        foreach (DepartmentCode::cases() as $code) {
            Department::query()->firstOrCreate(
                ['name' => $code->value],
                ['description' => $descriptions[$code->value] ?? null]
            );
        }

        Schema::table('skills', function (Blueprint $table) {
            $table->foreignId('department_id')->nullable()->after('category')->constrained()->cascadeOnDelete();
        });

        $departmentIds = DB::table('departments')
            ->whereIn('name', DepartmentCode::values())
            ->pluck('id', 'name')
            ->all();

        $prefixes = [
            'IT — ' => DepartmentCode::IT->value,
            'HR — ' => DepartmentCode::HR->value,
            'Sales — ' => DepartmentCode::SALES->value,
        ];

        foreach (DB::table('skills')->cursor() as $row) {
            $name = $row->name;
            $departmentName = DepartmentCode::IT->value;
            $newName = $name;

            foreach ($prefixes as $prefix => $deptName) {
                if (str_starts_with($name, $prefix)) {
                    $departmentName = $deptName;
                    $newName = trim(substr($name, strlen($prefix)));

                    break;
                }
            }

            $departmentId = $departmentIds[$departmentName] ?? null;
            if ($departmentId === null) {
                continue;
            }

            DB::table('skills')->where('id', $row->id)->update([
                'name' => $newName,
                'department_id' => $departmentId,
            ]);
        }

        DB::table('skills')->whereNull('department_id')->delete();

        Schema::table('skills', function (Blueprint $table) {
            $table->dropUnique(['name']);
        });

        Schema::table('skills', function (Blueprint $table) {
            $table->unique(['department_id', 'name']);
        });
    }

    public function down(): void
    {
        Schema::table('skills', function (Blueprint $table) {
            $table->dropUnique(['department_id', 'name']);
        });

        Schema::table('skills', function (Blueprint $table) {
            $table->dropForeign(['department_id']);
            $table->dropColumn('department_id');
        });

        Schema::table('skills', function (Blueprint $table) {
            $table->unique('name');
        });
    }
};
