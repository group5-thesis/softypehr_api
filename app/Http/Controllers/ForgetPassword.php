<?php

namespace App\Http\Controllers;
use DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Input;

class ForgetPassword extends Controller
{
    public function CheckingEmail(Request $request){
        try {
            $email = $request->email;
            $emails = DB::select('call RetrieveEmail');
            if(in_array($email, $emails))
            {
                echo "Yes, Email: $email does exits in array";
            }else{
                return SendingPin();
            }
  
        } catch (\Exception $e) {
            $response = ['data' => $e, "error" => true, "message" => $e->getMessage()];
            return response()->json($response, 500);
        }
    }   

    public function SendingPin(){
        try {
            echo "try";
        } catch (\Exception $e) {
            
        }
    }   

    
}
