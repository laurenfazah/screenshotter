<?php

/*
|--------------------------------------------------------------------------
| Routes File
|--------------------------------------------------------------------------
|
| Here is where you will register all of the routes in an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

use Jenssegers\Agent\Agent;

$agent = new Agent();

if ($agent->isDesktop()) {

    Route::get('/', function () {
        return view('pages/main');
    });

    Route::get('/error', function () {
        return view('pages/error');
    });

    Route::post('grabShots', 'ScreenshotsController@grabShots');

} else {

    Route::get('/', function () {
        return view('pages/mobile');
    });
}


/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| This route group applies the "web" middleware group to every route
| it contains. The "web" middleware group is defined in your HTTP
| kernel and includes session state, CSRF protection, and more.
|
*/

Route::group(['middleware' => ['web']], function () {
    //
});
