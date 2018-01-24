<?php
/**
 * This <fitness-counter.com> project created by :
 * Name         : syafiq
 * Date / Time  : 21 January 2018, 9:39 PM.
 * Email        : syafiq.rezpector@gmail.com
 * Github       : syafiqq
 */

namespace App\Http\Controllers\Organizer;


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
        return view("layout.organizer.dashboard.home.organizer_dashboard_home_{$this->theme}");
    }
}

?>
