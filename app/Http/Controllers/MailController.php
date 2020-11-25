<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Result;
use Mail;

class MailController extends Controller
{
    protected $receiver;
    protected $subject;
    protected $html;
    protected $content;

    public function sendEmail($receiver, $code, $subject)
    {
        $this->receiver = $receiver;
        $this->subject = $subject;
        try {
            $path = public_path('mail/mail.html');
            $file = file_get_contents($path);
            $this->html = str_replace("ACCESS_CODE", "<br>" . $code, $file);
            Mail::send([], [], function ($message) {
                $message->to($this->receiver)
                    ->subject($this->subject)
                    ->from('softypeapi@gmail.com', 'Softype Mail Service')
                    ->setBody($this->html, 'text/html');
            });
            return Result::setData(['status' => 'success']);
        } catch (\Exception $e) {
            \Log::info("mail : " . $e->getMessage());
            return Result::setError($e->getMessage());
        }
    }

    public function SendEmailNotification($type, $data)
    {
        $this->receiver = $data['receiver'];
        $name = $data['name'];

        try {
            $file = 'GenericMessage.html';
            $path = public_path("mail/$file");
            $content = file_get_contents($path);

            switch ($type) {
                case "RESOLVED_LEAVE_REQUEST":
                    //done
                    $status = $data['status'];
                    $this->subject = "Leave Request Notification";
                    $approver = $data['approver'];
                    $this->html = str_replace("{{MESSAGE}}", "Hi $name , your Leave request has been $status by $approver.", $content);
                    break;
                case "RESOLVED_OFFICE_REQUEST":
                    $approver = $data['approver'];
                    $this->html = str_replace("{{MESSAGE}}", "Hi $name, $ticketNo has been resolved.", $content);
                    break;
                case "PASSWORD_RESET":
                    $this->subject = 'Password Reset Notification.';
                    $this->html = str_replace("{{MESSAGE}}", "Hi $name, Your Password has been reset.<br> New Password is <i>Softype@100</i>", $content);
                    break;
                case "PASSWORD_CHANGED":
                    $this->subject = 'Password Changed Successfully.';
                    $this->html = str_replace("{{MESSAGE}}", "Hi $name, Your Password has been changed successfully.", $content);
                    break;
                case "GREETINGS":
                    $day = date("l");
                    $this->subject = "Greetings";
                    $this->html = str_replace("{{MESSAGE}}", "Good day $name!,<br> Have a happy $day!", $content);
                    break;
                case "NEW_LEAVE_REQUEST":
                    //done
                    $this->subject = "New Leave Request";
                    $name = $data['name'];
                    if ($data['forwarded'] == true) {
                        # code...
                        $this->html = str_replace("{{MESSAGE}}", $data['message'], $content);
                    } else {
                        $this->html = str_replace("{{MESSAGE}}", "New Leave Request from $name", $content);
                    }
                    break;
                default:
                    break;
            }
            
            $this->html = str_replace("{{url}}", "<a href='" . env('FRONTEND_URL') . "'> View in app now.</a>", $this->html);

            Mail::send([], [], function ($message) {
                $message->to($this->receiver)
                    ->subject($this->subject)
                    ->from('softypeapi@gmail.com', 'Softype Mail Service')
                    ->setBody($this->html, 'text/html');
            });
            return Result::setData(['status' => 'success']);
        } catch (\Exception $e) {
            \Log::info("mail : " . $e->getMessage());
            return Result::setError($e->getMessage());
        }
    }
    public function SendEmailNotificationV1($type, $data)
    {

        $this->receiver = $data['receiver'];
        try {
            $file = '';
            $path = '';

            switch ($type) {
                case "RESOLVED_LEAVE_REQUEST":
                    $file = 'LeaveRequest_Approve.html';
                    $path = public_path("mail/$file");
                    $content = file_get_contents($path);
                    $status = $data['status'];
                    $approver = $data['approver'];
                    $date_from = $data['date_from '];
                    $date_to = $data['date_to '];
                    $this->html = str_replace("{{status}}", $status, $content);
                    $this->html = str_replace("{{approver}}", $approver, $this->html);
                    $this->html = str_replace("{{date_from}}", $date_from, $this->html);
                    $this->html = str_replace("{{date_to}}", $date_to, $this->html);
                    break;
                case "RESOLVED_OFFICE_REQUEST":
                    $file = 'OfficeRequest_Close.html';
                    $path = public_path("mail/$file");
                    $content = file_get_contents($path);
                    $name = $data['name'];
                    $ticketNo = $data['ticketNo'];
                    $this->subject = 'Resolved Office Supply Request';
                    $this->html = str_replace("{{MESSAGE}}", "HI $name,<br> $ticketNo has been resolved.", $content);
                    break;
                case "PASSWORD_RESET":
                    $this->subject = 'Password Reset Notification.';
                    $file = 'Password_Reset.html';
                    break;
                case "PASSWORD_CHANGED":
                    $this->subject = 'Password Changed Successfully.';
                    $file = 'Password_Success.html';
                    break;
                case "GREETINGS":
                    $day = date("l");
                    $this->subject = "Greetings";
                    $file = 'OfficeRequest_Close.html';
                    $path = public_path("mail/$file");
                    $content = file_get_contents($path);
                    $name = $data['name'];
                    $this->html = str_replace("{{MESSAGE}}", "Good day $name !,<br> Have a happy $day!", $content);
                    break;
                case "NEW_LEAVE_REQUEST":
                    $this->subject = "New Leave Request";
                    $file = 'OfficeRequest_Close.html';
                    $path = public_path("mail/$file");
                    $content = file_get_contents($path);
                    $name = $data['name'];
                    if ($data['forwarded'] == true) {
                        # code...
                        $this->html = str_replace("{{MESSAGE}}", $data['message'], $content);
                    } else {
                        $this->html = str_replace("{{MESSAGE}}", "New Leave Request from $name", $content);
                        $this->html = str_replace("{{url}}", "<a href='" . env('FRONTEND_URL') . "'>View more details</a>", $this->html);
                    }
                    break;
                default:
                    break;
            }
            ;
            Mail::send([], [], function ($message) {
                $message->to($this->receiver)
                    ->subject($this->subject)
                    ->from('softypeapi@gmail.com', 'Softype Mail Service')
                    ->setBody($this->html, 'text/html');
            });
            return Result::setData(['status' => 'success']);
        } catch (\Exception $e) {
            \Log::info("mail : " . $e->getMessage());
            return Result::setError($e->getMessage());
        }
    }

    public function sendPushNotification(string $type, string $message)
    {
        event(new App\Events\MyEvent('pusher'));
    }
}
