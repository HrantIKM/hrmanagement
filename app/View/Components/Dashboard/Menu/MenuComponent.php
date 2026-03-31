<?php

namespace App\View\Components\Dashboard\Menu;

use App\Models\Menu\Menu;
use App\View\Components\Dashboard\Base;
use Illuminate\Contracts\View\View;

class MenuComponent extends Base
{
    public function __construct()
    {
    }

    /**
     * Function to render Menu view.
     */
    public function render(): View
    {
        $groupName = Menu::admin()
            ->whereNull('parent_id')
            ->with('subMenu')
            ->orderBy('sort_order')
            ->get()
            ->groupBy('group_name');

        return $this->dashboardComponent('menu.menu-component', [
            'groupName' => $groupName,
        ]);
    }
}
