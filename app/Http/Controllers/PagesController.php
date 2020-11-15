<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Session;

class PagesController extends Controller
{


  public function uploadFile(Request $request)
  {

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
   
        //  // Redirect to index
        //  return redirect()->action('PagesController@index');
  }
}