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
// use QrCode;

class EmployeeController extends Controller
{
    public function createEmployee(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'firstname' => 'required',
            'lastname' => 'required',
            'mobileno' => 'required',
            'birthdate' => 'required',
            'email' => 'required|unique:employee|email',
            'gender' => 'required',
            'street' => 'required',
            'city' => 'required',
            'country' => 'required',
            'roleId' => 'required'
        ]);

        if ($validator->fails()) {
            $messages = json_encode($validator->messages());
            $errors = $validator->errors();
            $response = ['data' => $errors->all(), 'error' => true, 'message' => $messages];
            return response()->json($response, 400);
        } else {
            DB::beginTransaction();
            try {
                $employee = DB::select(
                    'call CreateEmployee(?,?,?,?,?,?,?,?,?,?,?)',
                    array(
                        $request->firstname, $request->middlename, $request->lastname, $request->mobileno,
                        $request->gender, $request->email, $request->birthdate,
                        $request->street, $request->city, $request->country, $request->roleId
                    )
                );
                $result = collect($employee);
                $employee_id = $result[0]->id;

                if ($employee) {
                    $firstName = $request->firstname;
                    $lastName = $request->lastname;
                    $username = Str::lower($firstName[0] . $lastName . $employee_id);
                    $defaultPassword = Hash::make('Softype@100');
                    $file = 'qrcode/' . $username . '_' . $employee_id . '.svg';
                    \QrCode::size(250)->format('svg')->generate(json_encode($result[0]), public_path($file));
                    DB::select(
                        'call CreateEmployeeAccount(?,?,?,?,?)',
                        array($username, $defaultPassword, $file, $employee_id, $request->roleId)
                    );
                }
                DB::commit();
                $response = $this->retrieveLimitedEmployee($employee_id);
                return $response;
            } catch (\Exception $e) {
                DB::rollBack();
                $response = ['data' => $e, "error" => true, "message" => $e->getMessage()];
                return response()->json($response, 500);
            }
        }
    }

    public function retrieveEmployees()
    {
        try {
            $employees = DB::select('call RetrieveEmployees()');
            $result = collect($employees);
            $response = ['data' => ['employee_information' => $result], 'error' => false, 'message' => 'success'];
            return response()->json($response, 200);
        } catch (\Exception $e) {
            $response = ['data' => $e, "error" => true, "message" => $e->getMessage()];
            return response()->json($response, 500);
        }

    }

    public function retrieveLimitedEmployee($id)
    {
        try {
            $employee = DB::select('call RetrieveLimitedEmployee(?)', array($id));
            $result = collect($employee);
            $response = ['data' => ['employee_information' => $result], 'error' => false, 'message' => 'success'];
            return response()->json($response, 200);
        } catch (\Exception $e) {
            $response = ['data' => $e, "error" => true, "message" => $e->getMessage()];
            return response()->json($response, 500);
        }
    }

    public function retrieveEmployeeByDepartment($id)
    {
        try {
            $employees = DB::select('call RetrieveEmployeeByDepartment(?)', array($id));
            $result = collect($employees);
            $response = ['data' => ['employee_information' => $result], 'error' => false, 'message' => 'success'];
            return response()->json($response, 200);
        } catch (\Exception $e) {
            $response = ['data' => $e, "error" => true, "message" => $e->getMessage()];
            return response()->json($response, 500);
        }
    }

    public function retrieveEmployeeByManager($id)
    {
        try {
            $employees = DB::select('call RetrieveEmployeeByManager(?)', array($id));
            $result = collect($employees);
            $response = ['data' => ['employee_information' => $result], 'error' => false, 'message' => 'success'];
            return response()->json($response, 200);
        } catch (\Exception $e) {
            $response = ['data' => $e, "error" => true, "message" => $e->getMessage()];
            return response()->json($response, 500);
        }
    }

    public function updateEmployee(Request $request)
    {
        try {
            DB::beginTransaction();
            $updated_employee = DB::select(
                'call UpdateEmployee(?,?,?,?,?,?,?,?,?,?,?,?)',
                array(
                    $request->employeeId,
                    $request->firstname,
                    $request->middlename,
                    $request->lastname,
                    $request->mobileno,
                    $request->gender,
                    $request->email,
                    $request->birthdate,
                    $request->street,
                    $request->city,
                    $request->country,
                    $request->roleId
                )
            );
            $result = collect($updated_employee);
            DB::commit();
            dd($result);
        } catch (\Exception $e) {
            DB::rollback();
            $response = ['data' => $e, "error" => true, "message" => $e->getMessage()];
            return response()->json($response, 500);
        }
    }

    public function deleteEmployee($id)
    {
        try {
            DB::beginTransaction();
            $deleted_employee = DB::select('call DeleteEmployee(?)', array($id));
            $response = ['error' => false, 'message' => 'success'];
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            $response = ['data' => $e, "error" => true, "message" => $e->getMessage()];
            return response()->json($response, 500);
        }
    }

    public function retrieveEmployeeProfile(Request $request)
    {
        try {
            $employee = DB::select('call UserGetProfile(?)', array($request->userId));
            return response()->json($employee, Response::HTTP_OK);
        } catch (\Exception $e) {
            $response = ['data' => $e, "error" => true, "message" => $e->getMessage()];
            return response()->json($response, 500);
        }
    }

}
