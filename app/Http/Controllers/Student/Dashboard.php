<?php
/**
 * This <fitness-counter.com> project created by :
 * Name         : syafiq
 * Date / Time  : 25 January 2018, 7:52 PM.
 * Email        : syafiq.rezpector@gmail.com
 * Github       : syafiqq
 */

namespace App\Http\Controllers\Student;


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
        return view("layout.student.dashboard.home.student_dashboard_home_{$this->theme}");
    }
}

?>
