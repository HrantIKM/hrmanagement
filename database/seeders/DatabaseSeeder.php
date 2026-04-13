<?php

namespace Database\Seeders;

use App\Models\User\User;
use Database\Seeders\Department\DepartmentSeeder;
use Database\Seeders\Menu\MenuSeeder;
use Database\Seeders\Position\PositionSeeder;
use Database\Seeders\RoleAndPermission\RoleAndPermissionSeeder;
use Database\Seeders\Skill\SkillHrSeeder;
use Database\Seeders\Skill\SkillItSeeder;
use Database\Seeders\Skill\SkillSalesSeeder;
use Database\Seeders\User\AdminUserSeeder;
use Database\Seeders\User\UserSeeder;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $shouldResetAll = (bool) env('SEED_TRUNCATE_ALL', false);
        if ($shouldResetAll) {
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');

            $tables = [
                'roles',
                'permissions',
                'model_has_permissions',
                'model_has_roles',
                'role_has_permissions',
                'timesheets',
                'tasks',
                'project_user',
                'reviews',
                'payslips',
                'salaries',
                'goals',
                'skill_user',
                'candidate_skill',
                'skill_vacancy',
                'applications',
                'candidates',
                'vacancies',
                'skills',
                'positions',
                'departments',
            ];

            foreach ($tables as $table) {
                if (Schema::hasTable($table)) {
                    DB::table($table)->truncate();
                }
            }

            if (Schema::hasTable('users')) {
                User::truncate();
            }

            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        }

        $this->call([
            RoleAndPermissionSeeder::class,
            DepartmentSeeder::class,
            PositionSeeder::class,
            SkillItSeeder::class,
            SkillHrSeeder::class,
            SkillSalesSeeder::class,
            AdminUserSeeder::class,
            UserSeeder::class,
            MenuSeeder::class,
        ]);
    }
}
