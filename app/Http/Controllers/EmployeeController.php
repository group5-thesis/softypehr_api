<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Employee;

class EmployeeController extends Controller
{
    //
    public function createEmployee(Request $request){
        dd($request->all());
        $employee = User::create(['firstname' => $request->firstname, 'middlename' => $request->middlename, 'lastname' => $request->lastname, 'birthdate' => $request->birthdate, 'email' => $request->email, 'gender' => $request->gender, 'image' => $request->image, 'address' => $request->address, 'street' => $request->street, 'city' => $request->city]);
        return response()->json($employee, Response::HTTP_OK);
    }

    public function updateEmployee(Request $request){
        dd($request->all());
    }

    public function retrieveEmployees(Reqeust $request){
        dd($request->all());
    }

    public function deleteEmployee(Reqeust $request){
        dd($request->all());
    }
}
