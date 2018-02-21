<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class Event extends Controller
{
    /**
     * Dashboard constructor.
     */
    public function __construct()
    {
        parent::__construct();
    }

    public function getOverview($event)
    {
        $meta = [
            'event' => $event
        ];

        return view("layout.admin.event.overview.admin_event_overview_{$this->theme}", compact('meta'));
    }

    public function getManagementRegistrar($event)
    {
        $meta = [
            'event' => $event
        ];

        return view("layout.admin.event.management.registrar.admin_event_management_registrar_{$this->theme}", compact('meta'));
    }

    public function getManagementTester($event)
    {
        $meta = [
            'event' => $event
        ];

        return view("layout.admin.event.management.tester.admin_event_management_tester_{$this->theme}", compact('meta'));
    }
}
