<?php

namespace Database\Seeders\RoleAndPermission;

use App\Models\RoleAndPermission\Enums\RoleType;
use App\Models\RoleAndPermission\Role;
use Illuminate\Database\Seeder;

class RoleAndPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * Role And Permission Seeder.
     *
     * @return void
     */
    public function run()
    {
        foreach (RoleType::ALL as $role) {
            Role::query()->create([
                'name' => $role,
                'guard_name' => 'web',
            ]);
        }
    }
}
