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
                return Result::setError('', 'Email address not found', 401);
            } else {
                $addRecoveryCode = DB::select('call AddRecoveryCode(?)', [$email]);
                $codes = collect($addRecoveryCode);
                $mail = new MailController();
                return $mail->sendEmail($email, $codes[0]->code, 'Account Recovery Code');
            }
        } catch (\Exception $e) {
            return Result::setError($e->getMessage());
        }
    }

    public function changePassword(Request $request)
    {
        try {
            DB::beginTransaction();
            $change_password = DB::select('ChangePassword(?,?,?,?)', array(
                $request->userId,
                $request->current_password,
                $request->new_password,
                $request->password_confirmation
            ));
            $response = ['error' => false, 'message' => 'success'];
            DB::commit();
            return Result::setData($response);
        } catch (\Exception $e) {
            DB::rollback();
            return Result::setError($e->getMessage());
        }
    }
}