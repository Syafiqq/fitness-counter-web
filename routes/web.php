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
        Route::get('/register', 'RegisterController@getRegister')->name('register');
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
        Route::prefix('/management')->group(function () use ($group) {
            Route::get('/registrar', 'Event@getManagementRegistrar')->name("{$group}.event.management.registrar");
            Route::get('/tester', 'Event@getManagementTester')->name("{$group}.event.management.tester");
        });
        Route::prefix('/report')->group(function () use ($group) {
            Route::get('/evaluation', 'Event@getEvaluationReport')->name("{$group}.event.report.evaluation");
            Route::get('/health', 'Event@getHealthReport')->name("{$group}.event.report.health");
        });
        Route::prefix('/publish')->group(function () use ($group) {
            Route::post('/evaluation', 'Event@getPublishEvaluation')->name("{$group}.event.publish.evaluation");
            Route::post('/health/list', 'Event@getPublishHealth')->name("{$group}.event.publish.health.list");
            Route::post('/health/bunch', 'Event@getPublishHealthReport')->name("{$group}.event.publish.health.bunch");
            Route::post('/health/once', 'Event@getPublishHealthReportOnce')->name("{$group}.event.publish.health.once");
        });
    });
});
$group = 'registrar';
Route::prefix("/$group")->namespace('Registrar')->middleware(['web', 'auth', "role:$group"])->group(function () use ($group) {
    Route::get('/home', 'Dashboard@getHome')->name("{$group}.dashboard.home");
    Route::prefix("/event/{event}")->middleware(['event.valid'])->group(function () use ($group) {
        Route::get('/', 'Event@getOverview')->name("{$group}.event.overview");
        Route::post('/queue/add', 'Event@postQueueAddApi')->name("{$group}.event.queue.add")->middleware(['filter.request:json']);
    });
});
