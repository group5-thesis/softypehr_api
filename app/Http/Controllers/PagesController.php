<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Session;

class PagesController extends Controller
{
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
   
         // Redirect to index
         return redirect()->action('PagesController@index');
      }
}
