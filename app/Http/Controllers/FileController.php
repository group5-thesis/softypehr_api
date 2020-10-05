<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\View;
use App\Http\Controllers\Controller;
class FileController extends Controller
{
    //
    public function serve($folder, $filename){
        $dir = $folder."/".$filename;
        $path = public_path()."/".$dir;
        return File::get($path);
    }

    public function store(Request $request)
    {
        
        // $validator = Validator::make($request->all(), [
        //     'price' => 'required|regex:/^\d+(\.\d{1,2})?$/',
        //     'productCategory' => 'required|string|max:255',
        //     'productName' => 'required|string|max:255',
        // ]);
        // if($validator->fails()){
        //     return response()->json($validator->errors()->toJson(), 400);
        // }

        try{
            $imageName = time().'.'.$request->file->getClientOriginalExtension();
            $request->file->move(public_path('images'), $imageName);
            $response = ['data' => [],'error' => false, 'message' => "Success!"];
            return response()->json($response, 200);
        }catch(\Exception $e){
            $response = ['data' => $e, "error" => true, "message" => $e->getMessage()];
            return response()->json($response, 500);
        }
       
        
        // return response()->json(['success'=>'You have successfully upload image.']);
    }

}
