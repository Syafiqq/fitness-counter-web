<?php
/**
 * This <fitness-counter.com> project created by :
 * Name         : syafiq
 * Date / Time  : 26 January 2018, 10:04 PM.
 * Email        : syafiq.rezpector@gmail.com
 * Github       : syafiqq
 */

namespace App\Http\Controllers\Organizer;


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

    public function getCreate()
    {
        return view("layout.organizer.event.create.organizer_event_create_{$this->theme}");
    }
}

?>
