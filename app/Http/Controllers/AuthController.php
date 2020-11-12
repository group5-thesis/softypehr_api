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
use App\Http\Controllers\MailController;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        try {
            $username = $request->input('username'); // this the input from front end
            $password = $request->input('password');
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
                    $response = [];
                    foreach ($employee as $key => $value) {
                        $response = [
                            'access_token' => $access_token,
                            'account_information' => $employee,
                        ];
                    }
                    return Result::setData($response);
                } else {
                    // error password
                    return Result::setError('', "Invalid Credentials!", 401);
                }
            } else {
                 // error: user not found
                return Result::setError('', "Invalid Credentials!", 401);
            }
            // response()->json(["data" => $leave_request, "error" => false, "message" => "ok"], 200);
        } catch (\Exception $e) {
            return Result::setError($e->getMessage());//response()->json(["data" => $e, "error" => true, "message" =>$error_message], 500);
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
                                'street' => $value->street,
                                'city' => $value->city,
                                'country' => $value->country,
                            ]
                        ], 'error' => false, 'message' => 'success'];
                    }
                    return response()->json($response, 200);
                } else {
                  // error password
                    $response = ['data' => [], 'error' => true, 'message' => "Password didn't matched!"];
                    return response()->json($response, 405);
                }
            } else {
               // error: user not found
                $response = ['data' => [], 'error' => true, 'message' => 'User not found!'];
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
                                'mobile_no' => $value->mobileno, 'gender' => $value->gender, 'birthdate' => $value->birthdate, 'street' => $value->street, 'city' => $value->city, 'country' => $value->country
                            ]
                        ], 'error' => false, 'message' => 'success'];
                    }
                    return response()->json($response, 200);
                } else {
                  // error password
                    $response = ['data' => [], 'error' => true, 'message' => "Password didn't matched!"];
                    return response()->json($response, 405);
                }
            } else {
               // error: user not found
                $response = ['data' => [], 'error' => true, 'message' => 'User not found!'];
                return response()->json($response, 405);
            }
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

    public function forgotPassword(Request $request)
    {
        $email = $request->email;
        try {
            $result = DB::select('call CheckUserEmail(?)', array($email));
            $user = collect($result);
            if ($user[0]->isExist === 0) {
                return Result::setError('Email address not found', 401);
            } else {
                $code = substr(time() , \Str::length(time())-6 ,\Str::length(time()));
                $addRecoveryCode = DB::select('call AddRecoveryCode(?, ? ,?)', [$email ,$code , now()]);
                $mail = new MailController();
                return $mail->sendEmail($email, $code, 'Account Recovery Code');
            }
        } catch (\Exception $e) {
            return Result::setError("auth : ".$e->getMessage());
        }
    }

     public function updatePassword(Request $request)
     {
        \Log::info("update password");

         DB::beginTransaction();
        try {
            $query = DB::select('call UserGetInfoByEmail(?)' ,[$request->email]);
            $results = collect($query);
            \Log::info(json_encode($results));
            $changePasswordQuery = DB::select('call UpdatePassword(?,?)' ,[Hash::make($request->new_password) , $results[0]->userId]);
            $response = ['result' => 'Password changed successfully.'];
            DB::commit();
            return Result::setData($response);
        } catch (\Exception $e) {
            \Log::info("err : ".$e->getMessage());
            DB::rollback();
            return Result::setError($e->getMessage());
        }
     }

    public function changePassword(Request $request)
    {
        if ($request->isOtp == 1) {
            return $this->updatePassword($request);
        }
        DB::beginTransaction();
        try {
            $query = DB::select('call UserGetCurrentPassword(?)' ,[ $request->userId]);
            $results = collect($query);
            if (Hash::check($current_password, $results[0]->password)) {
                $changePasswordQuery = DB::select('call UpdatePassword(?,?)' ,[Hash::make($request->new_password) , $request->userId]);
                $response = ['result' => 'Password changed successfully.'];
                DB::commit();
                return Result::setData($response);
            }else{
                return Result::setError("" ,"Password incorrect." , 401);
            }
        } catch (\Exception $e) {
            DB::rollback();
            return Result::setError($e->getMessage());
        }
    }

    public function verifyOTP(Request $request)
    {
        $params = [$request->email , $request->OTP ,0 ];
        $error_message = "Invalid Verification Code!";
        DB::beginTransaction();
        try{ 
            $query = DB::select('call verifyRecoveryCode(?,?,?)' ,$params);
            $result = collect($query);
            \Log::info(json_encode($result));

            if (sizeof($result) < 1) {
                \Log::info("no result");
                return Result::setError('' , "Invalid Verification Code!", 401);
            }
            $startTime = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $result[0]->created_at);
            $endTime = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', now());
            $totalDuration = $endTime->diffInMinutes($startTime);
            // $startTime =\Carbon\Carbon::parse(now());
            // $endTime =\Carbon\Carbon::parse( $result[0]->created_at);
            // $totalDuration =  $startTime->diff($endTime);//->format('%I');
            $params[2] = 1;
            if (intval($totalDuration) > 10) {
                \Log::info("created : ".$startTime );
                \Log::info("now : ".$endTime );
                \Log::info("expired : ".$totalDuration );
                $query = DB::select('call verifyRecoveryCode(?,?,?)' ,$params);
                return Result::setError('' ,  "Invalid Verification Code!" , 401);               
            }
            $query = DB::select('call verifyRecoveryCode(?,?,?)' ,$params);
            DB::commit();
            return Result::setData(["success" =>"true"]);
         } catch (\Exception $e) {
            \Log::info("error ". $e->getMessage());

            DB::rollback();
            return Result::setError($e->getMessage());
        }
    }
}
