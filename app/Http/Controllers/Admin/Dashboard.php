<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class Dashboard extends Controller
{
    /**
     * Dashboard constructor.
     */
    public function __construct()
    {
        parent::__construct();
    }

    public function getHome()
    {
        return view("layout.admin.dashboard.home.admin_dashboard_home_{$this->theme}");
    }
}
