<?php

namespace App\Models;

class Result {
    public static function setError( $exception="" , $message="Somehing went wrong", $statusCode = 500){ 
        $errMessage=$message;
        \Log::error( $errMessage.=$exception);
        if (env('IS_DEV')) {
            if ($exception !='') {
                $errMessage.=$exception;
            }
        }

        return response()->json(["error"=>true , "message"=>$errMessage],$statusCode);
    }
    public static  function setData($data ,$message="ok"){
        return response()->json(["error"=>false ,"data"=>$data, "message"=>$message],200);
    }
} 

?>