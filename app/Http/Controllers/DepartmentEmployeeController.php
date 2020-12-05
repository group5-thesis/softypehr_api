<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use DB;
use App\Models\Result;
use App\Http\Controllers\MailController;


class DepartmentEmployeeController extends Controller
{
    public function addDepartmentEmployee(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'employeeId' => 'required',
            'department_managerId' => 'required',
            'department_headId' => 'required'
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
                    array($request->employeeId, $request->department_managerId, $request->department_headId)
                );
                $result = collect($employee);
                $department_employeeId = $result[0]->id;
                DB::commit();
                MailController::sendPushNotification('EmployeeUpdateNotification');
                $response = $this->retrieveLimitedDepartmentEmployee($department_employeeId);
                return $response;
            } catch (\Exception $e) {
                DB::rollback();
                return Result::setError( $e->getMessage() , 500) ;
            }
        }
    }

    public function deleteDepartmentEmployee(Request $request)
    {
        DB::beginTransaction();
        try {
            $deleted_department_employee = DB::select(
                'call DeleteDepartmentEmployee(?)',
                array($request->id)
            );
            $response = ['error' => false, 'message' => 'success'];
            DB::commit();
            MailController::sendPushNotification('EmployeeUpdateNotification');
            return Result::setData($response);
        } catch (\Exception $e) {
            DB::rollback();
            return Result::setError( $e->getMessage() , 500) ;
        }
    }

    public function retrieveLimitedDepartmentEmployee($id)
    {
        try {
            $department_employee = DB::select(
                'call RetrieveLimitedDepartmentEmployee(?)',
                array($id)
            );
            $result = collect($department_employee);
            return Result::setData(["employee_information" => $result]);
        } catch (\Exception $e) {
            return Result::setError( $e->getMessage() , 500) ;
        }
    }

    public function retrieveDepartmentEmployees()
    {
        try {
            $department_employees = DB::select('call RetrieveDepartmentEmployees()');
            $result = collect($department_employees);
            return Result::setData(["employee_information" => $result]);
        } catch (\Exception $e) {
            return Result::setError( $e->getMessage() , 500) ;
        }
    }

    public function updateDepartmentEmployee(Request $request)
    {
        DB::beginTransaction();
        try {
            $updated_department_employee = DB::select(
                'call UpdateDepartmentEmployee(?,?,?,?)',
                array($request->id, $request->employeeId, $request->department_headId, $request->department_managerId)
            );
            DB::commit();
            $result = collect($updated_department_employee);
            $department_employeeId = $result[0]->id;
            MailController::sendPushNotification('EmployeeUpdateNotification');        
            $response = $this->retrieveLimitedDepartmentEmployee($department_employeeId);
            return $response;
        } catch (\Exception $e) {
            DB::rollback();
            return Result::setError( $e->getMessage() , 500) ;
        }
    }
}
