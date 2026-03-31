<?php

namespace App\Http\Controllers\Website;

use App\Contracts\IBaseRepository;
use App\Http\Controllers\Controller;
use App\Services\BaseService;
use App\Traits\Helpers\ResponseFunctions;
use Illuminate\Contracts\View\View;

abstract class BaseController extends Controller
{
    use ResponseFunctions;

    final protected const DASHBOARD_VIEW_PREFIX = 'components.';

    protected BaseService $service;

    protected IBaseRepository $repository;

    protected function view(string $view, array $vars = [], string $viewMode = 'add'): View
    {
        $vars['viewMode'] = $viewMode;

        return view(self::DASHBOARD_VIEW_PREFIX . '.' . $view, $vars);
    }

    protected function renderView(string $view, array $vars = []): string
    {
        return view(self::DASHBOARD_VIEW_PREFIX . '.' . $view, $vars)->render();
    }
}
