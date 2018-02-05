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
}
