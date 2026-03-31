<?php

namespace Database\Seeders\User;

use App\Models\RoleAndPermission\Enums\RoleType;
use App\Models\User\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

/**
 * Class AdminUserSeeder.
 */
class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * Admin Seeder.
     *
     * @return void
     */
    public function run()
    {
        $user = User::query()->firstOrCreate([
            'first_name' => 'Admin',
            'last_name' => 'Admin',
            'email' => 'admin@admin.com',
            'password' => Hash::make('m67u12n7i938c789h1429v'),
        ]);

        $user->assignRole(RoleType::ADMIN);
    }
}
