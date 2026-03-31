<?php

namespace App\Http\Controllers\Dashboard;

use Illuminate\Contracts\View\View;

class DashboardController extends BaseController
{
    public function index(): View
    {
        return $this->dashboardView('dashboard');
    }
}
