<?php

namespace App\Http\Controllers\Registrar;

use App\Http\Controllers\Controller;

class Dashboard extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getHome()
    {
        return view("layout.registrar.dashboard.home.registrar_dashboard_home_{$this->theme}");
    }
}
