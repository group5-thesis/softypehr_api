<?php

namespace App\Http\Controllers;

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
}
