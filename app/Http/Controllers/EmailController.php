<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class EmailController extends Controller
{
    public function send(Request $request)
    {
        // $data = array('name'=>"$userName", "body" => "Test mail");
        $credentials = [
            "sender_email" =>env("MAIL_FROM_ADDRESS"),
            "sender_name"=>env("MAIL_FROM_NAME"),
            "receiver_email"=>$request->email,
            "receiver_name"=>$request->name
        ];
        try{
            \Mail::raw('Test', function($message){
                $message->from($credentials['sender_email']);
                $message->sender($credentials['sender_email'], $credentials['sender_name']);
                $message->to($credentials['receiver_email'], $credentials['receiver_name']);
                $message->replyTo($credentials['sender_email'], $credentials['sender_name']);
                $message->subject('YOL TORRES');
            });
            return response()->json("ok", 200);
        } catch(\Exception $e){
            return ["error" => $e];
            // return response()->json(json_encode(["error" => $e]), 500);
        }
    }
}
