<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\User;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{

    public function createEmployeeAccount($data)
    {
        $user = User::create(
            [
                'username' => $data['username'],
                'password' => Hash::make($data['password']), // default password is Softype@100
                'email' => $data['email'],
                'qr_code' => $data['qr_code'],
                'employeeId' => $data['employeeId']
            ]
        );
    }

    public function retrieveUsers()
    {
        $users = User::get();
        return response()->json($users, Response::HTTP_OK);
    }

    public function retrieveUser(Request $request)
    {
        $user = User::where('id', '=', $request->id)->get();
        return response()->json($user, Response::HTTP_OK);
    }

    public function updateUser(Request $request)
    {
        $user = User::where('id', '=', $request->id)->update(['password' => $request->updatePassword]);
        return response()->json($user, Response::HTTP_OK);
    }

    public function deleteUser(Request $request)
    {
        $user = User::where('id', '=', $request->id)->delete();
        return response()->json($user, Response::HTTP_OK);
    }
}
