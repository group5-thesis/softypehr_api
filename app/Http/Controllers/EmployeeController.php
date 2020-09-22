<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Employee;
use App\Models\Account;
use Illuminate\Support\Str;
use DB;
use App\Http\Controllers\AccountController;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Hash;

class EmployeeController extends Controller
{
    public function createEmployee(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'firstname' => 'required',
            'middlename' => 'required',
            'lastname' => 'required',
            'mobileno' => 'required',
            'birthdate' => 'required',
            'email' => 'required|unique:employee|email',
            'gender' => 'required',
            'street' => 'required',
            'city' => 'required',
            'country' => 'required',
            'roleId' => 'required',
        ]);

        if ($validator->fails()) {
            $messages = $validator->messages();
            $response = ['data' => ['error' => true, 'message' => $messages]];
            return response()->json($response, 400);
            // return Redirect::to('/create_employee')->with('message', 'Register Failed');
        } else {
            $employee = DB::select(
                'call CreateEmployee(?,?,?,?,?,?,?,?,?,?,?,?)',
                array(
                    $request->firstname, $request->middlename, $request->lastname, $request->mobileno,
                    $request->gender, $request->email, $request->birthdate, $request->profileImage,
                    $request->street, $request->city, $request->country, $request->roleId
                )
            );
            $result = collect($employee);
            $employee_id = $result[0]->id;

            try {
                if ($employee) {
                    $firstName = '';
                    $lastName = '';
                    $firstName = $request->firstname;
                    $lastName = $request->lastname;
                    $username = Str::lower($firstName[0] . $lastName);
                    $defaultPassword = Hash::make('Softype@100');

                    $file = 'qrcode/' . $username . '_' . $employee_id . '.svg';
                    $qrcode = \QrCode::size(250)->format('svg')->generate(json_encode($result[0]), public_path($file));

                    $user = DB::select(
                        'call CreateEmployeeAccount(?,?,?,?,?)',
                        array($username, $request->email, $defaultPassword, $file, $employee_id)
                    );

                    $response = ['data' => ['account_information' => $result, 'error' => false, 'message' => 'success']];

                    return response()->json($response, 200);
                }
            } catch (\Exception $e) {
                return $e->getMessage();
            }
        }
    }

    public function retrieveEmployees()
    {
        $employees = Employee::RetrieveEmployees();
        return response()->json($employees, Response::HTTP_OK);
    }

    public function retrieveEmployeeLimited(Request $request)
    {
        $employee = DB::select('call RetrieveLimitedEmployee(?)', array($request->id));
        return response()->json($employee, Response::HTTP_OK);
    }

    public function updateEmployee(Request $request)
    {
        $employee = Employee::where('id', '=', $request->id)->update(['password' => $request->updatePassword]);
        return response()->json($employee, Response::HTTP_OK);
    }

    public function deleteEmployee(Request $request)
    {
        // $employee = Employee::where('id', '=', $request->id)->delete();
        $employee = Employee::select('call DeleteEmployee(?)', array($request->id));
        return response()->json($employee, Response::HTTP_OK);
    }

    public function retrieveEmployeeProfile(Request $request){
        $employee = DB::select('call UserGetProfile(?)', array($request->userId));
        return response()->json($employee, Response::HTTP_OK);
    }

}
