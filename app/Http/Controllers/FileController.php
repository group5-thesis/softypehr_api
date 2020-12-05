<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\View;
use App\Http\Controllers\Controller;
use App\Models\Result;
use DB;
use App\Http\Controllers\MailController;


class FileController extends Controller
{
    
    public function serve($folder, $filename){
        $dir = $folder."/".$filename;
        $path = public_path()."/".$dir;
        return File::get($path);
    }
    public function serveImage($filename){
        $path = public_path('images').'/'.$filename;
        if (!File::exists($path)) {
            $path = public_path('static').'/logo-sm.png';
        // abort(404);
        }
        $file = File::get($path);
        $type = File::mimeType($path);
        $response = Response::make($file, 200);
        $response->header("Content-Type", $type);
        return $response;
    }

    public function downloadFile($dir , $path)
    {
        try{
            return response()->file(public_path("$dir/$path"));
        }catch(Exception $e){
            return Result::setError($e->getMessage());
        }
    }

    public static function store($file)
    {
        try{
            $imageName = 'uploads_'.time().".".$file->getClientOriginalExtension();
            $file->move(public_path('images'), $imageName);
            return $imageName;
        }catch(\Exception $e){
            return Result::setError( $e->getMessage()) ;
        }
    }

    // public function addFile(Request $request)
    // {
    //     try{
    //         $images = ["jpg","png"];
    //         $docs = ["docs","pdf","txt"];
    //         $imageName = time().'.'.$request->file->getClientOriginalExtension();
    //         $extension = $request->file->getClientOriginalExtension();
    //         if (in_array($extension,$images)){
    //             $request->file->move(public_path('images'), $imageName);
    //             $path = public_path('images');
    //             $type = "img";
    //         }elseif (in_array($extension,$docs)){
    //             $request->file->move(public_path('documents'), $imageName);
    //             $path = public_path('documents');
    //             $type = "documents";
    //         }else{
    //             $request->file->move(public_path('others'), $imageName);
    //             $path = public_path('others');
    //             $type = "others";
    //         }
            
    //         $file = \DB::select(
    //             'call AddFile(?,?,?,?,?,?)', 
    //             array(
    //                 $request->employeeId,
    //                 $imageName,
    //                 $path, 
    //                 $type,
    //                 $request->description)
    //             );
            
    //         $result = collect($file);
    //         $file_id = $result[0]->id;
    //         // $response = $this->retrieveLimitedFile($file_id);
    //         return response()->json($file_id, 200);
    //     }catch(\Exception $e){
    //         return $e;
    //     }
    // }


    public function addFile(Request $request)
    {
        DB::beginTransaction();
        try{
            $fileName =$request->file('file')->getClientOriginalName();
            $path ='uploads_'.time().'.'.$request->file->getClientOriginalExtension();
            $type = $request->type;
            $dir = public_path($type);
            $request->file->move($dir, $path);
            $_file = DB::select(
                'call AddFile(?,?,?,?,?)', 
                array(
                    $request->employeeId,
                    $fileName,
                    $path, 
                    $type,
                    $request->description)
                );
            
            $result = collect($_file);
            $file_id = $result[0]->id;
            DB::commit();
            MailController::sendPushNotification('EmployeeUpdateNotification');
            return  Result::setData(["success"=>true]);
        }catch(\Exception $e){
            DB::rollback();
        
            return Result::setError( $e->getMessage()) ;
        }
    }


    public function retrieveFiles(){
        try {
            $files = DB::select('call RetrieveFiles()');
            $result = collect($files);
            return Result::setData(['files' => $result]);
        } catch (\Exception $e) {
            return Result::setError( $e->getMessage()) ;
        }
    }   

    public function deleteFile($id){
        try {
            DB::beginTransaction();
            $deleted_files = DB::select('call DeleteFile(?)', array($id));
            $response = ['error' => false, 'message' => 'success'];
            MailController::sendPushNotification('EmployeeUpdateNotification');
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            return Result::setError( $e->getMessage()) ;
        }
    }


    public function retrieveFilesByType($id)
    {
        try {
            $file = DB::select('call RetrieveByFileType(?)', array($id));
            $result = collect($file);
            $response = ['data' => ['files' => $result], 'error' => false, 'message' => 'success'];
            return response()->json($response, 200);
        } catch (\Exception $e) {
            return Result::setError( $e->getMessage());
        }
    }


    public function updateFile(Request $request)
    {
        try {
            DB::beginTransaction();
            $updated_file = DB::select(
                'call UpdateFile(?,?,?,?,?,?)',
                array(
                    $request->fileId,
                    $request->employeeId,
                    $request->imageName,
                    $request->path,
                    $request->type,
                    $request->description
                )
            );
            $result = collect($updated_file);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            return Result::setError( $e->getMessage()) ;
        }
    } 
}
