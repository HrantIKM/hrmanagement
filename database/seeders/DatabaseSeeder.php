<?php

namespace Database\Seeders;

use App\Models\User\User;
use Database\Seeders\Menu\MenuSeeder;
use Database\Seeders\RoleAndPermission\RoleAndPermissionSeeder;
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
        User::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $this->call([
            RoleAndPermissionSeeder::class,
            AdminUserSeeder::class,
            UserSeeder::class,
            MenuSeeder::class,
        ]);
    }
}
