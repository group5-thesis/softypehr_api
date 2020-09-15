<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Employee;
use App\Models\Account;
use Illuminate\Support\Str;
use DB;
use App\Http\Controllers\AccountController;

class EmployeeController extends Controller
{
    public function createEmployee(Request $request)
    {
        $employee = Employee::create(
            [
                'firstname' => $request->firstname,
                'middlename' => $request->middlename,
                'lastname' => $request->lastname,
                'mobileno' => $request->mobileno,
                'birthdate' => $request->birthdate,
                'email' => $request->email,
                'gender' => $request->gender,
                'profileImage' => $request->profileImage,
                'street' => $request->street,
                'city' => $request->city,
                'country' => $request->country,
                'roleId' => $request->roleId,
            ]
        );
        $firstName = '';
        $lastName = '';
        $firstName = $request->firstname;
        $lastName = $request->lastname;
        $firstLetter = $firstName[0];
        $username = Str::lower($firstLetter . $lastName);
        $defaultPassword = 'Softype@100';
        $accountEmployee = [
            'employeeId' => $employee->id,
            'username' => $username,
            'password' => $defaultPassword,
            'qr_code' => 'code here',
            'email' => $request->email
        ];

        app('App\Http\Controllers\UserController')->createEmployeeAccount($accountEmployee);

        return response()->json($employee, Response::HTTP_OK);
    }

    public function retrieveEmployees()
    {
        $employees = Employee::get();
        return response()->json($employees, Response::HTTP_OK);
    }

    public function retrieveEmployeeLimited(Request $request)
    {
        $employee = Employee::where('id', '=', $request->id)->get();
        return response()->json($employee, Response::HTTP_OK);
    }

    public function updateEmployee(Request $request)
    {
        $employee = Employee::where('id', '=', $request->id)->update(['password' => $request->updatePassword]);
        return response()->json($employee, Response::HTTP_OK);
    }

    public function deleteEmployee(Request $request)
    {
        $employee = Employee::where('id', '=', $request->id)->delete();
        return response()->json($employee, Response::HTTP_OK);
    }

}
