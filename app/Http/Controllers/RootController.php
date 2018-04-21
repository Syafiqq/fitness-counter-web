<?php

namespace App\Http\Controllers;

use Illuminate\Http\Response;

class RootController extends Controller
{
    /**
     * @return Response
     */
    public function welcome()
    {
        return view('welcome');
    }
}
