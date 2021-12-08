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
            $username = $request->input('username_email'); // this the input from front end
            $password = $request->input('password');
            $result = User::where('username', '=', $username)->first();
            if ($result) { // email exists in database
                if (Auth::attempt(['username' => $username, 'password' => $password])) {
                    // success
                    $token = self::getToken($username, $password);
                    $access_token = $token;
                    $result->save();
                    $employee = DB::select('call UserGetProfile(?)', array($result->id));
                    $res = collect($employee)[0];
                    if ($res->is_deactivated == 1 || $res->isActive != 1) {
                        return Result::setError('', "Account is inactive.Please contact the Administrator.", 401);
                    }
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
            return Result::setError($e->getMessage()); //response()->json(["data" => $e, "error" => true, "message" =>$error_message], 500);
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
                $query = DB::select('call UserGetInfoByEmail(?)', [$email]);
                $results = collect($query);
                $employees = DB::select("call UserGetProfile(?)", [$results[0]->userId]);
                $employee = collect($employees)[0];
                if ($employee->is_deactivated == 1 || $employee->isActive != 1) {
                    return Result::setError('', "Account is inactive.Please contact the Administrator.", 401);
                }
                $code = substr(time(), \Str::length(time()) - 6, \Str::length(time()));
                $addRecoveryCode = DB::select('call AddRecoveryCode(?, ? ,?)', [$email, $code, now()]);
                $mail = new MailController();
                return $mail->sendEmail($email, $code, 'Account Recovery Code');
            }
        } catch (\Exception $e) {
            return Result::setError("auth : " . $e->getMessage());
        }
    }

    public function updatePassword(Request $request)
    {
        DB::beginTransaction();
        try {
            $query = DB::select('call UserGetInfoByEmail(?)', [$request->email]);
            $results = collect($query);
            $employees = DB::select("call UserGetProfile(?)", [$results[0]->userId]);
            $employee = collect($employees)[0];
            if ($employee->is_deactivated == 1 || $employee->isActive != 1) {
                return Result::setError('', "Account is inactive.Please contact the Administrator.", 401);
            }
            $changePasswordQuery = DB::select('call UpdatePassword(?,?)', [Hash::make($request->new_password), $results[0]->userId]);
            $response = ['result' => 'Password changed successfully.'];
            $mail = new MailController();
            $mail->SendEmailNotification("PASSWORD_CHANGED", [
                "name" => $employee->firstname,
                "receiver" => $request->email
            ]);
            DB::commit();
            return Result::setData($response);
        } catch (\Exception $e) {
            \Log::info("err : " . $e->getMessage());
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
            $employees = DB::select("call UserGetProfile(?)", [$request->userId]);
            $employee = collect($employees)[0];
            if ($employee->is_deactivated == 1 || $employee->isActive != 1) {
                return Result::setError('', "Account is inactive.Please contact the Administrator.", 401);
            }
            $query = DB::select('call UserGetCurrentPassword(?)', [$request->userId]);
            $results = collect($query);
            if (Hash::check($request->current_password, $results[0]->password)) {
                $changePasswordQuery = DB::select('call UpdatePassword(?,?)', [Hash::make($request->new_password), $request->userId]);
                $response = ['result' => 'Password changed successfully.'];
                DB::commit();
                return Result::setData($response);
            } else {
                return Result::setError("", "Password incorrect.", 401);
            }
        } catch (\Exception $e) {
            DB::rollback();
            return Result::setError($e->getMessage());
        }
    }
    public function verifyOTP(Request $request)
    {
        $params = [$request->email, $request->OTP, 0];
        $error_message = "Invalid Verification Code!";
        DB::beginTransaction();
        try {
            $query = DB::select('call verifyRecoveryCode(?,?,?)', $params);
            $result = collect($query);
            \Log::info(json_encode($result));

            if (sizeof($result) < 1) {
                \Log::info("no result");
                return Result::setError('', "Invalid Verification Code!", 401);
            }
            $startTime = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $result[0]->created_at);
            $endTime = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', now());
            $totalDuration = $endTime->diffInMinutes($startTime);
            // $startTime =\Carbon\Carbon::parse(now());
            // $endTime =\Carbon\Carbon::parse( $result[0]->created_at);
            // $totalDuration =  $startTime->diff($endTime);//->format('%I');
            $params[2] = 1;
            if (intval($totalDuration) > 10) {
                $query = DB::select('call verifyRecoveryCode(?,?,?)', $params);
                return Result::setError('',  "Invalid Verification Code!", 401);
            }
            $query = DB::select('call verifyRecoveryCode(?,?,?)', $params);
            DB::commit();
            return Result::setData(["success" => "true"]);
        } catch (\Exception $e) {
            \Log::info("error " . $e->getMessage());

            DB::rollback();
            return Result::setError($e->getMessage());
        }
    }
}
