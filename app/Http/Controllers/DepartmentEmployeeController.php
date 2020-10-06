<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DepartmentEmployeeController extends Controller
{
    // Department Employee
    public function addDepartmentEmployee(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'employeeId' => 'required',
            'department_managerId' => 'required',
            'departmentId' => 'required'
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
                    'call AddDepartmentEmployee(?,?,?)',
                    array($request->employeeId, $request->department_managerId, $request->departmentId, )
                );
                $result = collect($employee);
                $employee_id = $result[0]->id;
                dd($result);
                DB::commit();
                $response = $this->retrieveLimitedDepartmentEmployee($employee_id);
                return $response;
            } catch (\Exception $e) {
                DB::rollback();
                $response = ['data' => $e, "error" => true, "message" => $e->getMessage()];
                return response()->json($response, 500);
            }
        }
    }

    public function addDepartmentManager(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'department_managerId' => 'required',
            'departmentId' => 'required'
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
                    'call AddDepartmentManager(?,?)',
                    array($request->department_managerId, $request->departmentId, )
                );
                $result = collect($employee);
                DB::commit();
                // $employee_id = $result[0]->id;
                dd($result);
                // $response = $this->retrieveLimitedDepartmentEmployee($employee_id);
                // return $response;
            } catch (\Exception $e) {
                DB::rollback();
                $response = ['data' => $e, "error" => true, "message" => $e->getMessage()];
                return response()->json($response, 500);
            }
        }
    }

    public function deleteDepartmentEmployee(Request $request)
    {
        try {
            DB::beginTransaction();
            $deleted_department_employee = DB::select(
                'call DeleteDepartmentEmployee(?,?,?)',
                array($request->employeeId, $request->department_managerId, $request->departmentId)
            );
            DB::commit();
            dd($deleted_department_employee);
            $response = ['data' => [], 'error' => false, 'message' => 'Successfully Deleted'];
            return response()->json($response, 200);
        } catch (\Exception $e) {
            DB::rollback();
            $response = ['data' => $e, "error" => true, "message" => $e->getMessage()];
            return response()->json($response, 500);
        }
    }

    public function retrieveLimitedDepartmentEmployee(Request $request)
    {
        try {
            $employee = DB::select(
                'call RetrieveLimitedDepartmentEmployee(?,?,?)',
                array($request->employeeId, $request->department_managerId, $request->departmentId)
            );
            $result = collect($employee);
            $response = ['data' => ['employee_information' => $result], 'error' => false, 'message' => 'success'];
            return response()->json($response, 200);
        } catch (\Exception $e) {
            $response = ['data' => $e, "error" => true, "message" => $e->getMessage()];
            return response()->json($response, 500);
        }
    }

    public function retrieveDepartmentEmployees($id)
    {
        try {
            $department_employees = DB::select('call RetrieveEmployeeByDepartment(?)', array($id));
            $result = collect($department_employees);
            $response = ['data' => ['employee_information' => $result], 'error' => false, 'message' => 'success'];
            return response()->json($response, 200);
        } catch (\Exception $e) {
            $response = ['data' => $e, "error" => true, "message" => $e->getMessage()];
            return response()->json($response, 500);
        }
    }

    public function changeDepartmentManager(Request $request)
    {
        try {
            DB::beginTransaction();
            $change_manager = DB::select(
                'call ChangeDepartmentManager(?,?)',
                array($request->departmentId, $request->department_managerId)
            );
            dd($change_manager);
            DB::commit();
        } catch (\Exception $e) {
            $response = ['data' => $e, "error" => true, "message" => $e->getMessage()];
            return response()->json($response, 500);
        }
    }
}
