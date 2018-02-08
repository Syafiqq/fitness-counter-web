<?php

namespace App\Http\Controllers\Registrar;

use App\Http\Controllers\Controller;

class Event extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getOverview()
    {
        return view("layout.registrar.event.overview.registrar_event_overview_{$this->theme}");
    }
}
