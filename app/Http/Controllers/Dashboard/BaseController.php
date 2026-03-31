<?php

namespace App\Http\Controllers\Dashboard;

use App\Contracts\IBaseRepository;
use App\Http\Controllers\Controller;
use App\Services\BaseService;
use App\Traits\Helpers\ResponseFunctions;
use Illuminate\Contracts\View\View;

abstract class BaseController extends Controller
{
    use ResponseFunctions;

    final protected const DASHBOARD_VIEW_PREFIX = 'components.dashboard';

    protected BaseService $service;

    protected IBaseRepository $repository;

    protected function dashboardView(string $view, array $vars = [], string $viewMode = 'add'): View
    {
        $vars['viewMode'] = $viewMode;

        $this->generateSubHeaderData($view, $viewMode);

        return view(self::DASHBOARD_VIEW_PREFIX . '.' . $view, $vars);
    }

    protected function renderDashboardView(string $view, array $vars = []): string
    {
        return view(self::DASHBOARD_VIEW_PREFIX . '.' . $view, $vars)->render();
    }

    private function generateSubHeaderData(string $view, string $viewMode): void
    {
        // Form mode
        view()->composer('*.form', function () use ($view, $viewMode) {
            view()->share('subHeaderData', ['pageName' => $view . '.' . $viewMode]);
        });

        // Index mode
        view()->composer('*.index', function () use ($view) {
            view()->share('subHeaderData', ['pageName' => $view]);
            view()->share('isIndexPage', true);
        });
    }
}
