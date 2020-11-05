<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Mail;
use App\Models\Result;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class MailController extends Controller {
   public function basic_email() {
       try{

           $data = array('name'=>"Softype");  
           Mail::send(['text'=>'mail'], $data, function($message) {
              $message->to('yoltorres24@gmail.com', 'Softype')->subject
                 ('Laravel Basic Testing Mail');
              $message->from('the.future2499@gmail.com','Softype');
           });
           echo "Basic Email Sent. Check your inbox.";
       }catch(\Exception $e){
          return "e";
       }
   }
   public static function sendEmail($receiver , $content , $subject) {
       try{
        $data = array('name'=>"Softype");
        Mail::send(['text'=>$content], $data, function($message) {
           $message->to($receiver, 'Softype')->subject
              ($subject);
           $message->from('softypeapi@gmail.com','Softype');
        });
        return ['status'=>'success'];
       }catch(\Exception $e){
        return Result::setError($e->getMessage());
       }
   }
   public function attachment_email() {
      $data = array('name'=>"Softype");
      Mail::send('mail', $data, function($message) {
         $message->to('11aresearchers@gmail.com', 'Softype')->subject
            ('Softype Attachment');
         $message->attach('');
         $message->attach('');
         $message->from('softypeapi@gmail.com','Softype');
      });
      echo "Email Sent with attachment. Check your inbox.";
   }
}
