<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class AuthController extends Controller
{
    // public function authenticate(Request $request)
    // {
    //     $username = $request->input('username');
    //     $password = $request->input('password');

    //     $user = User::where(['username' => $username, 'password' => $password])->first();

    //     try {
    //         // verify the credentials and create a token for the user
    //         if (!$access_token = JWTAuth::fromUser($user)) {
    //             return response()->json(['error' => 'invalid_credentials'], 401);
    //         }
    //     } catch (JWTException $e) {
    //         // something went wrong
    //         return response()->json(['error' => 'could_not_create_token'], 500);
    //     }
    //     // if no errors are encountered we can return a JWT
    //     return response()->json(compact('access_token'));
    // }

    public function login(Request $request)
    {
        $user = \App\User::where('email', $request->email)->get()->first();
        if ($user && \Hash::check($request->password, $user->password)) // The passwords match...
        {
            $token = self::getToken($request->email, $request->password);
            $auth_token = $token;
            $user->save();
            $response = ['success'=>true, 'data'=>['id'=>$user->id,'auth_token'=>$auth_token, 'email'=>$user->email]];
        }
        else
          $response = ['success'=>false, 'data'=>'Record does not exists'];


        return response()->json($response, 201);
    }

    private function getToken($email, $password)
    {
        $token = null;
        try {
            if (!$token = JWTAuth::attempt( ['email'=>$email, 'password'=>$password])) {
                return response()->json([
                    'response' => 'error',
                    'message' => 'Password or email is invalid',
                    'token'=>$token
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
