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

class AuthController extends Controller
{
    public function login(Request $request){
            $email_or_username = $request->input('username_email'); // this the input from front end
              $isEmail = filter_var($email_or_username, FILTER_VALIDATE_EMAIL);
              $user_email = User::where($isEmail?'email':'username', '=', $request->input('username_email'))->first();
            //   dd($isEmail?'email':'username');
              if ($user_email) { // email exists in database
                  if (Auth::attempt([$isEmail?'email':'username' => $email_or_username, 'password' => $request->input('password')])) {
                    // success
                      $token = self::getToken($request->username_email, $request->password);
                      $access_token = $token;
                      $user_email->save();
                      $temp = 'test';
                      $employee = DB::select('call RetrieveLimitedEmployee(?)', array($user_email->employeeId));
                      foreach ($employee as $key => $value) {
                          $response = ['data' => [
                              'access_token' => $access_token,
                              'account_information' => [
                                'employee_id' => $user_email->id, 
                                'firstname' => $value->firstname, 
                                'middlename' => $value->middlename, 
                                'lastname' => $value->lastname, 
                                'role' => $value->roleId, 
                                'email' => $value->email,
                                'mobile_no' => $value->mobileno, 
                                'gender' => $value->gender, 
                                'birthdate' => $value->birthdate, 
                                'profileImage' => $value->profileImage, 
                                'street' => $value->street, 
                                'city' => $value->city, 
                                'country' => $value->country,
                                'qrCode' =>$user_email->qr_code
                              ]
                          ], 'error' => false, 'message' => 'success'];
                      }
                      return response()->json($response, 200);
                  } else {
                    // error password
                    $response = ['data' => [],'error' => true, 'message' => "Password didn't matched!"];
                    return response()->json($response, 405);
                  }
              } else {
                 // error: user not found
                 $response = ['data' => [] ,'error' => true, 'message' => 'User not found!'];
                  return response()->json($response, 405);
              }  
    }

    public function login1(Request $request)
    {
        $email_or_username = $request->input('username_email'); // this the input from front end

        if (filter_var($email_or_username, FILTER_VALIDATE_EMAIL)) { // user sent his email
            // check if user email exists in database
            $user_email = User::where('email', '=', $request->input('username_email'))->first();

            if ($user_email) { // email exists in database
                if (Auth::attempt(['email' => $email_or_username, 'password' => $request->input('password')])) {
                  // success
                    $token = self::getToken($request->username_email, $request->password);
                    $access_token = $token;
                    $user_email->save();
                    $temp = 'test';
                    $employee = DB::select('call RetrieveLimitedEmployee(?)', array($user_email->employeeId));
                    foreach ($employee as $key => $value) {
                        $response = ['data' => [
                            'id' => $user_email->id, 'access_token' => $access_token,
                            'account_information' => [
                                'firstname' => $value->firstname, 
                                'middlename' => $value->middlename, 
                                'lastname' => $value->lastname, 
                                'role' => $value->roleId, 
                                'email' => $value->email,
                                'mobile_no' => $value->mobileno, 
                                'gender' => $value->gender, 
                                'birthdate' => $value->birthdate, 
                                'profileImage' => $value->profileImage, 
                                'street' => $value->street, 
                                'city' => $value->city, 
                                'country' => $value->country,
                            ]
                        ], 'error' => false, 'message' => 'success'];
                    }
                    return response()->json($response, 200);
                } else {
                  // error password
                  $response = ['data' => [],'error' => true, 'message' => "Password didn't matched!"];
                  return response()->json($response, 405);
                }
            } else {
               // error: user not found
               $response = ['data' => [] ,'error' => true, 'message' => 'User not found!'];
                return response()->json($response, 405);
            }

        } else { // user sent username

            // check if username exists in database
            $user_username = User::where('username', '=', $request->input('username_email'))->first();

            if ($user_username) { // username exists in database
                if (Auth::attempt(['username' => $email_or_username, 'password' => $request->input('password')])) {
                  // success
                    $token = self::getToken($request->username_email, $request->password);
                    $access_token = $token;
                    $user_username->save();
                    $temp = 'test';
                    $employee = DB::select('call RetrieveLimitedEmployee(?)', array($user_username->employeeId));
                    foreach ($employee as $key => $value) {
                        $response = ['data' => [
                            'id' => $user_username->id, 'access_token' => $access_token,
                            'account_information' => [
                                'firstname' => $value->firstname, 'middlename' => $value->middlename, 'lastname' => $value->lastname, 'role' => $value->roleId, 'email' => $value->email,
                                'mobile_no' => $value->mobileno, 'gender' => $value->gender, 'birthdate' => $value->birthdate, 'profileImage' => $value->profileImage, 'street' => $value->street, 'city' => $value->city, 'country' => $value->country
                            ]
                        ], 'error' => false, 'message' => 'success'];
                    }
                    return response()->json($response, 200);
                } else {
                  // error password
                    $response = ['data' => [],'error' => true, 'message' => "Password didn't matched!"];
                    return response()->json($response, 405);
                }
            } else {
               // error: user not found
                $response = ['data' => [] ,'error' => true, 'message' => 'User not found!'];
                return response()->json($response, 405);
            }
        }
    }

    private function getToken($email_username, $password)
    {
        $token = null;
        try {
            if (!$token = JWTAuth::attempt([filter_var($email_username, FILTER_VALIDATE_EMAIL) ? 'email' : 'username' => $email_username, 'password' => $password])) {
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
