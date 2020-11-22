<?php

use Illuminate\Support\Facades\Route;
use Symfony\Component\HttpKernel\Event\RequestEvent;
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

Route::get('/', function () {
    return view('welcome');
});

Route::get('/testing', function () {
    $payload = [
        "type"=>"error",
        "data"=>["q"=>"tests"],
    ];
    event(new App\Events\MyEvent( $payload));
    return "Event has been sent!";
});

Route::get('/test', function () {
    return "server running";
});

Route::get('/file/{dir}/{path}', 'FileController@downloadFile');