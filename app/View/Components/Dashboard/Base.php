<?php

namespace App\View\Components\Dashboard;

use Illuminate\View\Component;
use Illuminate\View\View;

class Base extends Component
{
    public const DASHBOARD_COMPONENTS_PREFIX = 'components.dashboard';

    public function render()
    {
        // TODO: Implement render() method.
    }

    /**
     * Function to show dashboard component.
     */
    public function dashboardComponent(?string $componentName = null, ?array $vars = []): View
    {
        return view(
            view: self::DASHBOARD_COMPONENTS_PREFIX . ($componentName ? '.' . $componentName : $componentName),
            data: $vars
        );
    }
}
