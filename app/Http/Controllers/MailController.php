<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Mail;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class MailController extends Controller {
   public function basic_email() {
      $data = array('name'=>"Softype");  
      Mail::send(['text'=>'mail'], $data, function($message) {
         $message->to('11aresearchers@gmail.com', 'Tutorials Point')->subject
            ('Laravel Basic Testing Mail');
         $message->from('softypeapi@gmail.com','Softype');
      });
      echo "Basic Email Sent. Check your inbox.";
   }
   public function html_email() {
      $data = array('name'=>"Softype");
      Mail::send('mail', $data, function($message) {
         $message->to('11aresearchers@gmail.com', 'Tutorials Point')->subject
            ('Test Email');
         $message->from('softypeapi@gmail.com','Softype');
      });
      echo "HTML Email Sent. Check your inbox.";
   }
   public function attachment_email() {
      $data = array('name'=>"Softype");
      Mail::send('mail', $data, function($message) {
         $message->to('11aresearchers@gmail.com', 'Tutorials Point')->subject
            ('Softype Attachment');
         $message->attach('');
         $message->attach('');
         $message->from('softypeapi@gmail.com','Softype');
      });
      echo "Email Sent with attachment. Check your inbox.";
   }
}