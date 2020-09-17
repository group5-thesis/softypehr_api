<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class Emailsend extends Controller
{
    public function store(Request $request)
    {
        $to_name = $request->input('userName');
        $to_email = $request->input('userEmail');
        $data = array('name'=>"$userName", "body" => "Test mail");

        Mail::send('this is the file for the email body', $data, function($message) use ($to_name,$to_email){
        $message->to('$userEmail');
        $message->subject('Request email');
        $message->from('softypeapi@gmail.com','SOftype');
    });
       echo "Email sent";
    }
}
