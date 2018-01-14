<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

/** @var \Illuminate\Contracts\Auth\StatefulGuard $auth */
$auth = \Illuminate\Support\Facades\Auth::getFacadeRoot();

use Illuminate\Support\Facades\Route;

Route::get('/', function () use ($auth) {
    //return view('welcome');
    //$result = $auth->attempt(['email' => 'syafiq.rezpector@gmail.com', 'password' => 'password']);
    $result = \Illuminate\Support\Facades\Auth::check();

    \Illuminate\Support\Facades\Log::debug(\Illuminate\Support\Facades\Session::all());
    \Illuminate\Support\Facades\Log::debug($result);

    return "Hello";
});
