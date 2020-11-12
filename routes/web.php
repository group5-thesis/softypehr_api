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
    event(new App\Events\MyEvent('Welcome'));
    return "Event has been sent!";
});

Route::get('/test', function () {
    return "Server is running..." . \Hash::check("yol", '$2y$10$iJXhPCmA5I4AWKCsebFbuOl.LitknOFkXjKGXT7FJFxhpxBodfgYO') ? "valid" : "incorrect";
});

Route::get('/file/{dir}/{path}', 'FileController@downloadFile');