<?php

namespace App\Http\Controllers;

use App\Events\MyEvent;
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
    public function sendEmailNotice($receiver)
    {
        $this->receiver = $receiver;
        try {
            $path = public_path('mail/Disabled_Account.html');
            $file = file_get_contents($path);
            $this->html = $file;
            Mail::send([], [], function ($message) {
                $message->to($this->receiver)
                    ->subject("Account Suspension Notification")
                    ->from('softypeapi@gmail.com', 'Softype Mail Service')
                    ->setBody($this->html, 'text/html');
            });
            return Result::setData(['status' => 'success']);
        } catch (\Exception $e) {
            \Log::info("mail : " . $e->getMessage());
            return Result::setError($e->getMessage());
        }
    }
    public function sendEmailWelcome($receiver)
    {
        $this->receiver = $receiver;
        try {
            $path = public_path('mail/GenericMessage.html');
            $content = file_get_contents($path);
            $this->html = str_replace("{{MESSAGE}}", "Welcome back! ,<br/>    Your account has been <b>Enabled</b>.", $content);
            Mail::send([], [], function ($message) {
                $message->to($this->receiver)
                    ->subject("Account Enabled Notification")
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
                    $ticketNo = $data['ticketNo'];
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
    public static function sendPushNotification(string $type, $data = null)
    {
        $payload = [
            "type" => $type,
            "data" => $data,
        ];

        event(new MyEvent($payload));
    }
}
