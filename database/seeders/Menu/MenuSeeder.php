<?php

namespace Database\Seeders\Menu;

use App\Models\Menu\Menu;
use App\Models\RoleAndPermission\Enums\RoleType;
use Illuminate\Database\Seeder;

class MenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Menu::truncate();
        Menu::reguard();

        $menus = [
            [
                'title' => 'Translation Manager',
                'slug' => 'translation-manager',
                'url' => route('dashboard.translation.manager', [], false),
                'icon' => 'fas fa-language fa-fw',
                'type' => 'admin',
                'role' => [RoleType::ADMIN, RoleType::USER],
            ],
            [
                'title' => 'Users',
                'slug' => 'users',
                'url' => route('dashboard.users.index', [], false),
                'icon' => 'fas fa-users fa-fw',
                'type' => 'admin',
                'role' => [RoleType::ADMIN],
            ],
            [
                'title' => 'Departments',
                'slug' => 'departments',
                'url' => route('dashboard.departments.index', [], false),
                'icon' => 'fas fa-sitemap fa-fw',
                'type' => 'admin',
                'role' => [RoleType::ADMIN],
            ],
            [
                'title' => 'Positions',
                'slug' => 'positions',
                'url' => route('dashboard.positions.index', [], false),
                'icon' => 'fas fa-briefcase fa-fw',
                'type' => 'admin',
                'role' => [RoleType::ADMIN],
            ],
            [
                'title' => 'Skills',
                'slug' => 'skills',
                'url' => route('dashboard.skills.index', [], false),
                'icon' => 'fas fa-award fa-fw',
                'type' => 'admin',
                'role' => [RoleType::ADMIN],
            ],
            [
                'title' => 'Vacancies',
                'slug' => 'vacancies',
                'url' => route('dashboard.vacancies.index', [], false),
                'icon' => 'fas fa-door-open fa-fw',
                'type' => 'admin',
                'role' => [RoleType::ADMIN],
            ],
            [
                'title' => 'Candidates',
                'slug' => 'candidates',
                'url' => route('dashboard.candidates.index', [], false),
                'icon' => 'fas fa-user-tie fa-fw',
                'type' => 'admin',
                'role' => [RoleType::ADMIN],
            ],
            [
                'title' => 'Projects',
                'slug' => 'projects',
                'url' => route('dashboard.projects.index', [], false),
                'icon' => 'fas fa-project-diagram fa-fw',
                'type' => 'admin',
                'role' => [RoleType::ADMIN],
            ],
            [
                'title' => 'Tasks',
                'slug' => 'tasks',
                'url' => route('dashboard.tasks.index', [], false),
                'icon' => 'fas fa-tasks fa-fw',
                'type' => 'admin',
                'role' => [RoleType::ADMIN],
            ],
            [
                'title' => 'Timesheets',
                'slug' => 'timesheets',
                'url' => route('dashboard.timesheets.index', [], false),
                'icon' => 'fas fa-clock fa-fw',
                'type' => 'admin',
                'role' => [RoleType::ADMIN],
            ],
            [
                'title' => 'Goals',
                'slug' => 'goals',
                'url' => route('dashboard.goals.index', [], false),
                'icon' => 'fas fa-bullseye fa-fw',
                'type' => 'admin',
                'role' => [RoleType::ADMIN],
            ],
            [
                'title' => 'Reviews',
                'slug' => 'reviews',
                'url' => route('dashboard.reviews.index', [], false),
                'icon' => 'fas fa-clipboard-check fa-fw',
                'type' => 'admin',
                'role' => [RoleType::ADMIN],
            ],
            [
                'title' => 'Salaries',
                'slug' => 'salaries',
                'url' => route('dashboard.salaries.index', [], false),
                'icon' => 'fas fa-money-bill-wave fa-fw',
                'type' => 'admin',
                'role' => [RoleType::ADMIN],
            ],
            [
                'title' => 'Payslips',
                'slug' => 'payslips',
                'url' => route('dashboard.payslips.index', [], false),
                'icon' => 'fas fa-file-invoice-dollar fa-fw',
                'type' => 'admin',
                'role' => [RoleType::ADMIN],
            ],
            [
                'title' => 'Articles',
                'slug' => 'articles',
                'url' => route('dashboard.articles.index', [], false),
                'icon' => 'far fa-newspaper fa-fw',
                'type' => 'admin',
                'role' => [RoleType::ADMIN, RoleType::USER],
                /*'sub' =>  [
                    [
                        'title' => 'Sub Article',
                        'slug' => 'sub_articles',
                        'url' => route('dashboard.articles.index', [], false),
                        'type' => 'admin',
                        'role' => [RoleType::ADMIN]
                    ]
                ],*/
            ],
            [
                'title' => 'Vue3',
                'slug' => 'vue-example',
                'url' => route('dashboard.vue-example.index', [], false),
                'icon' => 'fab fa-vuejs',
                'type' => 'admin',
                'role' => [RoleType::ADMIN],
            ],
        ];

        foreach ($menus as $key => $menu) {
            if (!isset($menu['sort_order'])) {
                $menu['sort_order'] = $key + 1;
            }

            $createdMenu = Menu::create($menu);

            foreach ($menu['sub'] ?? [] as $subMenu) {
                $subMenu['parent_id'] = $createdMenu->id;
                $createdSubMenu = Menu::create($subMenu);

                $createdSubMenu->assignRole($subMenu['role']);
            }

            $createdMenu->assignRole($menu['role']);
        }
    }
}
