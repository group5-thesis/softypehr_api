<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\User;

class UserController extends Controller
{
    //
    public function createUser(Request $request){
        // dd($request->all());
        $user = User::create(['username' => $request->username, 'password' => $request->password, 'email' => $request->email]);
        return response()->json($user, Response::HTTP_OK);
    }

    
}
