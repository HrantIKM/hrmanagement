<?php

namespace App\Http\Controllers\Website;

class HomeController extends BaseController
{
    public function index()
    {
        return $this->view('website.index');
    }
}
