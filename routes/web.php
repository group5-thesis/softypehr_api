<?php

use App\Http\Controllers\MailController;
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
        "type" => "error",
        "data" => ["q" => "tests"],
    ];
    event(new App\Events\MyEvent($payload));
    return "Event has been sent!";
});

Route::get('/test', function () {

    $payload = [
        "receiver" => "yoltorres24@gmail.com",
        "name" => "Lay",
        "approver" => "Leonilo",
        "status" => "rejected",
        "forwarded" => false,
        "ticketNo" => false,
    ];

    $mail = new MailController();
    return $mail->SendEmailNotification("RESOLVED_LEAVE_REQUEST", $payload);
    $mail->SendEmailNotification("RESOLVED_OFFICE_REQUEST", $payload);
    $mail->SendEmailNotification("PASSWORD_RESET", $payload);
    $mail->SendEmailNotification("PASSWORD_CHANGED", $payload);
    $mail->SendEmailNotification("GREETINGS", $payload);
    $mail->SendEmailNotification("NEW_LEAVE_REQUEST", $payload);
    $payload["forwarded"] = true;
    $payload["message"] = "5 forwarded Leave Request ";
    $mail->SendEmailNotification("NEW_LEAVE_REQUEST", $payload);
    return "server running";
});

Route::get('/file/{dir}/{path}', 'FileController@downloadFile');
