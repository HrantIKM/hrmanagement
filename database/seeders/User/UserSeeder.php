<?php

namespace Database\Seeders\User;

use App\Models\RoleAndPermission\Enums\RoleType;
use App\Models\User\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = User::factory()->count(5)->create();

        foreach ($users as $user) {
            $user->assignRole(RoleType::USER);
        }
    }
}
