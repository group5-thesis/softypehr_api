<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Illuminate\Support\Facades\Validator;

class DepartmentController extends Controller
{

    public function addDepartment(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required'
        ]);

        if ($validator->fails()) {
            $messages = json_encode($validator->messages());
            $errors = $validator->errors();
            $response = ['data' => $errors->all(), 'error' => true, 'message' => $messages];
            return response()->json($response, 400);
        } else {
            DB::beginTransaction();
            try {
                $department = DB::select(
                    'call AddDepartment(?)',
                    array($request->name)
                );
                $result = collect($department);
                $departmentId = $result[0]->id;
                DB::commit();
                $response = $this->retrieveLimitedDepartment($departmentId);
                return $response;
            } catch (\Exception $e) {
                DB::rollback();
                $response = ['data' => $e, "error" => true, "message" => $e->getMessage()];
                return response()->json($response, 500);
            }
        }
    }

    public function deleteDepartment($id)
    {
        DB::beginTransaction();
        try {
            $department = DB::select('call DeleteDepartment(?)', array($id));
            DB::commit();
            $response = ['error' => false, 'message' => 'success'];
            return response()->json($response, 200);
        } catch (\Exception $e) {
            DB::rollback();
            $response = ['data' => $e, "error" => true, "message" => $e->getMessage()];
            return response()->json($response, 500);
        }
    }

    public function updateDepartment(Request $request)
    {
        DB::beginTransaction();
        try {
            $department = DB::select(
                'call UpdateDepartment(?,?)',
                array(
                    $request->departmentId, $request->name
                )
            );
            DB::commit();
            $response = $this->retrieveLimitedDepartment($request->departmentId);
            return $response;
        } catch (\Exception $e) {
            DB::rollback();
            $response = ['data' => $e, "error" => true, "message" => $e->getMessage()];
            return response()->json($response, 500);
        }
    }

    public function retrieveLimitedDepartment($id)
    {
        try {
            $department = DB::select('call RetrieveLimitedDepartment(?)', array($id));
            $result = collect($department);
            $response = ['data' => ['department' => $result], 'error' => false, 'message' => 'success'];
            return response()->json($response, 200);
        } catch (\Exception $e) {
            $response = ['data' => $e, "error" => true, "message" => $e->getMessage()];
            return response()->json($response, 500);
        }
    }

    public function retrieveDepartments()
    {
        try {
            $department = DB::select('call RetrieveDepartments()');
            $result = collect($department);
            $response = ['data' => ['department' => $result], 'error' => false, 'message' => 'success'];
            return response()->json($response, 200);
        } catch (\Exception $e) {
            $response = ['data' => $e, "error" => true, "message" => $e->getMessage()];
            return response()->json($response, 500);
        }
    }

    public function retrieveDepartmentHeads()
    {
        try {
            $department_head = DB::select('call RetrieveDepartmentHeads()');
            $result = collect($department_head);
            $response = ['data' => ['department' => $result], 'error' => false, 'message' => 'success'];
            return response()->json($response, 200);
        } catch (\Exception $e) {
            $response = ['data' => $e, "error" => true, "message" => $e->getMessage()];
            return response()->json($response, 500);
        }
    }

    public function retrieveDepartmentManagers()
    {
        try {
            $department_managers = DB::select('call RetrieveDepartmentManagers()');
            $result = collect($department_managers);
            $response = ['data' => ['department' => $result], 'error' => false, 'message' => 'success'];
            return response()->json($response, 200);
        } catch (\Exception $e) {
            $response = ['data' => $e, "error" => true, "message" => $e->getMessage()];
            return response()->json($response, 500);
        }
    }



}
