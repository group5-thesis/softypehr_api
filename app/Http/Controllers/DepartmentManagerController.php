<?php

namespace App\Http\Controllers;

use App\Http\Controllers\MailController;
use App\Models\Result;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DepartmentManagerController extends Controller
{

    public function addDepartmentManager(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'department_manager' => 'required',
            'departmentId' => 'required',
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
                    array($request->departmentId, $request->department_manager)
                );
                $result = collect($employee);
                $employee_id = $result[0]->id;
                DB::commit();
                MailController::sendPushNotification('EmployeeUpdateNotification');
                $response = $this->retrieveLimitedDepartmentManager($employee_id);
                return $response;
            } catch (\Exception $e) {
                DB::rollback();
                return Result::setError($e->getMessage(), 500);
            }
        }
    }

    public function updateDepartmentManager(Request $request)
    {
        DB::beginTransaction();
        try {
            $updated_manager = DB::select(
                'call UpdateDepartmentManager(?,?,?)',
                array($request->id, $request->departmentId, $request->employeeId)
            );
            $result = collect($updated_manager);
            $employee_id = $result[0]->id;
            DB::commit();
            $response = $this->retrieveLimitedDepartmentManager($employee_id);
            return $response;
        } catch (\Exception $e) {
            DB::rollback();
            return Result::setError($e->getMessage(), 500);
        }
    }

    public function deleteDepartmentManager(Request $request)
    {
        DB::beginTransaction();
        try {
            $deleted_manager = DB::select('call DeleteDepartmentManager(?)', array($request->id));
            $response = ['error' => false, 'message' => 'success'];
            DB::commit();
            MailController::sendPushNotification('EmployeeUpdateNotification');
            return Result::setData($response);
        } catch (\Exception $e) {
            DB::rollback();
            return Result::setError($e->getMessage(), 500);
        }
    }

    public function retrieveLimitedDepartmentManager($id)
    {
        try {
            $department_manager = DB::select(
                'call RetrieveLimitedDepartmentManager(?)',
                array($id)
            );
            $result = collect($department_manager);
            return Result::setData(['department_manager_information' => $result]);
        } catch (\Exception $e) {
            return Result::setError($e->getMessage(), 500);
        }
    }

    public function retrieveDepartmentManagers()
    {
        try {
            $department_managers = DB::select(
                'call RetrieveDepartmentManagers()'
            );
            $result = collect($department_managers);
            return Result::setData(['department_manager_information' => $result]);
        } catch (\Exception $e) {
            return Result::setError($e->getMessage(), 500);
        }
    }
}
