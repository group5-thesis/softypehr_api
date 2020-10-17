<?php

namespace App\Models;

class Result {
    public static function setError($message , $statusCode){
        return response()->json(["error"=>true , "message"=>$message],$statusCode);
    }
    public static  function setData($data ,$message="ok"){
        return response()->json(["error"=>false ,"data"=>$data, "message"=>$message],200);
    }
} 

?>