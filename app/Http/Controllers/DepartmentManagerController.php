<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DepartmentManagerController extends Controller
{

    public function addDepartmentManager(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'department_manager' => 'required',
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
                    array($request->department_manager, $request->departmentId, )
                );
                $result = collect($employee);
                $employee_id = $result[0]->id;
                DB::commit();
                $response = $this->retrieveLimitedDepartmentManager($employee_id);
                return $response;
            } catch (\Exception $e) {
                DB::rollback();
                $response = ['data' => $e, "error" => true, "message" => $e->getMessage()];
                return response()->json($response, 500);
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
            DB::commit();
            $response = ['error' => false, 'message' => 'success'];
            return response()->json($response, 200);
        } catch (\Exception $e) {
            DB::rollback();
            $response = ['data' => $e, "error" => true, "message" => $e->getMessage()];
            return response()->json($response, 500);
        }
    }

    public function deleteDepartmentManager($id)
    {
        DB::beginTransaction();
        try {
            $deleted_manager = DB::select('call DeleteDepartmentManager(?)', array($id));
            DB::commit();
            $response = ['error' => false, 'message' => 'success'];
            return response()->json($response, 200);
        } catch (\Exception $e) {
            DB::rollback();
            $response = ['data' => $e, "error" => true, "message" => $e->getMessage()];
            return response()->json($response, 500);
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
            $response = ['data' => ['manager_information' => $result], 'error' => false, 'message' => 'success'];
            return response()->json($response, 200);
        } catch (\Exception $e) {
            $response = ['data' => $e, "error" => true, "message" => $e->getMessage()];
            return response()->json($response, 500);
        }
    }

    public function retrieveDepartmentManagers()
    {
        try {
            $department_managers = DB::select(
                'call RetrieveDepartmentManagers()'
            );
            $result = collect($department_managers);
            $response = ['data' => ['manager_information' => $result], 'error' => false, 'message' => 'success'];
            return response()->json($response, 200);
        } catch (\Exception $e) {
            $response = ['data' => $e, "error" => true, "message" => $e->getMessage()];
            return response()->json($response, 500);
        }
    }
}
