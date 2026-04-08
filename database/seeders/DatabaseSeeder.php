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

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('roles')->truncate();
        DB::table('permissions')->truncate();
        DB::table('model_has_permissions')->truncate();
        DB::table('model_has_roles')->truncate();
        DB::table('role_has_permissions')->truncate();
        DB::table('timesheets')->truncate();
        DB::table('tasks')->truncate();
        DB::table('project_user')->truncate();
        DB::table('reviews')->truncate();
        DB::table('payslips')->truncate();
        DB::table('salaries')->truncate();
        DB::table('goals')->truncate();
        DB::table('skill_user')->truncate();
        User::truncate();
        DB::table('candidate_skill')->truncate();
        DB::table('skill_vacancy')->truncate();
        DB::table('applications')->truncate();
        DB::table('candidates')->truncate();
        DB::table('vacancies')->truncate();
        DB::table('skills')->truncate();
        DB::table('positions')->truncate();
        DB::table('departments')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

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
