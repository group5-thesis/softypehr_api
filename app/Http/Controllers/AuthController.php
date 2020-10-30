<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Symfony\Component\Console\Input\Input;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Result;


class AuthController extends Controller
{
    public function login(Request $request){
        try{
            $username = $request->input('username'); // this the input from front end
            $password =  $request->input('password');
              $result = User::where('username', '=', $username)->first();
              if ($result) { // email exists in database
                  if (Auth::attempt(['username' => $username, 'password' => $password])) {
                    // success
                      $token = self::getToken($username, $password);
                      $access_token = $token;
                      $result->save();
                      $temp = 'test';
                      $employee = DB::select('call UserGetProfile(?)', array($result->id));
                    //   $employee = DB::select('call UserGetProfile(?)', array($result->employeeId));
                    $response=[]; 
                      foreach ($employee as $key => $value) {
                          $response = ['data' => [
                              'access_token' => $access_token,
                              'account_information' => $employee,
                          ], 'error' => false, 'message' => 'success'];
                      }
                      return Result::setData($response);
                  } else {
                    // error password
                    return Result::setError($e->getMessage() , "Invalid Credentials!" ,401);
                  }
              } else {
                 // error: user not found
                return Result::setError($e->getMessage() , "Invalid Credentials!" ,401 );
              }
            // response()->json(["data" => $leave_request, "error" => false, "message" => "ok"], 200);
            } catch (\Exception $e) {
                return  Result::setError($e->getMessage()) ;//response()->json(["data" => $e, "error" => true, "message" =>$error_message], 500);
            }

    }
    
    private function getToken($email_username, $password)
    {
        $token = null;
        try {
            if (!$token = JWTAuth::attempt(['username' => $email_username, 'password' => $password])) {
                return response()->json([
                    'response' => 'error',
                    'message' => 'Password or email/username is invalid',
                    'token' => $token
                ]);
            }
        } catch (JWTAuthException $e) {
            return response()->json([
                'response' => 'error',
                'message' => 'Token creation failed',
            ]);
        }
        return $token;
    }
}
