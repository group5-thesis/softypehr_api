<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Session;

class PagesController extends Controller
{
<<<<<<< HEAD
    public function index(){
        return view('index');
      }
      public function uploadFile(Request $request){
         if ($request->input('submit') != null ){
           $file = $request->file('file');
           // File Details
           $filename = $file->getClientOriginalName();
           $extension = $file->getClientOriginalExtension();
           $tempPath = $file->getRealPath();
           $fileSize = $file->getSize();
           $mimeType = $file->getMimeType();
   
           // file distinction
           $image_extension = array("jpg","jpeg","png");
           $docs_extension = array("pdf","docs","txt");
           // 2MB in Bytes
           $maxFileSize = 2097152;
   
           // Check file extension
           if(in_array(strtolower($extension),$image_extension)){
             if($fileSize <= $maxFileSize){
                $location = 'images';
                $file->move($location,$filename);
             }else{
                alert('File too large. File must be less than 2MB.');
             }
           }elseif(in_array(strtolower($extension),$docs_extension)){
            if($fileSize <= $maxFileSize){
               $location = 'documents';
               $file->move($location,$filename);
            }else{
               alert('File too large. File must be less than 2MB.');
            }
          }else{
            alert('file not valid');
          }
         }
=======

   
      public function uploadFile(Request $request){

        dd($request->file);
   
        // if ($request->input('submit') != null ){
   
        //    $file = $request->get('file');
   
        //    // File Details
        //    $filename = $file->getClientOriginalName();
        //    $extension = $file->getClientOriginalExtension();
        //    $tempPath = $file->getRealPath();
        //    $fileSize = $file->getSize();
        //    $mimeType = $file->getMimeType();
   
        //    // Valid File Extensions
        //    $valid_extension = array("jpg","jpeg","png","txt","pdf","docs");
   
        //    // 2MB in Bytes
        //    $maxFileSize = 2097152;
   
        //    // Check file extension
        //    if(in_array(strtolower($extension),$valid_extension)){
   
        //      // Check file size
        //      if($fileSize <= $maxFileSize){
   
        //         // File upload location
        //         $location = 'images';
   
        //         // Upload file
        //         $file->move($location,$filename);
                
        //         Session::flash('message','Upload Successful.');
        //      }else{
        //         Session::flash('message','File too large. File must be less than 2MB.');
        //      }
   
        //    }else{
        //       Session::flash('message','Invalid File Extension.');
        //    }
   
        //  }
>>>>>>> 54fecb88abaf59c067d23b340bc0ed0c40d8e2a2
   
        //  // Redirect to index
        //  return redirect()->action('PagesController@index');
      }
}
