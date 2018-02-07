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

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->middleware(['web', 'guest']);

Route::prefix('/auth')->namespace('Auth')->middleware(['web'])->group(function () {
    Route::middleware(['guest'])->group(function () {
        Route::get('/login', 'LoginController@getLogin')->name('login');
        Route::post('/login', 'LoginController@postLogin')->name('auth.login.post')->middleware(['extract.email.role']);
    });
    Route::middleware(['auth'])->group(function () {
        Route::get('/switch/{role}', 'RoleSwitchController@getSwitch')->name('auth.switch.role');
        Route::get('/logout', 'LoginController@getLogout')->name('logout');
    });
});
$group = 'admin';
Route::prefix("/$group")->namespace('Admin')->middleware(['web', 'auth', "role:$group"])->group(function () use ($group) {
    Route::get('/home', 'Dashboard@getHome')->name("{$group}.dashboard.home");
    Route::prefix("/event/{event}")->middleware(['event.valid'])->group(function () use ($group) {
        Route::get('/', 'Event@getOverview')->name("{$group}.event.overview");
    });
});
$group = 'registrar';
Route::prefix("/$group")->namespace('Registrar')->middleware(['web', 'auth', "role:$group"])->group(function () use ($group) {
    Route::get('/home', 'Dashboard@getHome')->name("{$group}.dashboard.home");
});
