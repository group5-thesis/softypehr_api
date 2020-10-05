<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Illuminate\Support\Facades\Validator;

class DepartmentController extends Controller
{
    // Department
    public function createDepartment(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'department_headId' => 'required'
        ]);

        if ($validator->fails()) {
            $messages = json_encode($validator->messages());
            $response = ['data' => [], 'error' => true, 'message' => $messages];
            return response()->json($response, 400);
        } else {
            DB::beginTransaction();
            try {
                $department = DB::select(
                    'call createDepartment(?,?)',
                    array($request->name, $request->department_headId)
                );
                $result = collect($department);
                $departmentId = $result[0]->id;
                DB::commit();
                $response = $this->retrieveLimitedDepartment($departmentId);
                return $response;
            } catch (\Exception $e) {
                DB::rollback();
                $response = ['data' => [], "error" => true, "message" => $e->getMessage()];
                return response()->json($response, 500);
            }
        }
    }

    public function deleteDepartment($id)
    {
        DB::beginTransaction();
        try {
            $department = DB::select('call deleteDepartment(?)', array($id));
            DB::commit();
            $response = ['data' => [], 'error' => false, 'message' => 'success'];
            return response()->json($response, 200);
        } catch (\Exception $e) {
            DB::rollback();
            $response = ['data' => [], "error" => true, "message" => $e->getMessage()];
            return response()->json($response, 500);
        }
    }

    public function updateDepartment(Request $request)
    {
        DB::beginTransaction();
        try {
            $department = DB::select(
                'call updateDepartment(?,?,?)',
                array(
                    $request->departmentId, $request->name, $request->employeeId,
                )
            );
            DB::commit();
            $response = $this->retrieveLimitedDepartment($request->departmentId);
            return $response;
        } catch (\Exception $e) {
            DB::rollback();
            $response = ['data' => [], "error" => true, "message" => $e->getMessage()];
            return response()->json($response, 500);
        }
    }

    public function retrieveLimitedDepartment($id)
    {
        try {
            $department = DB::select('call retrieveLimitedDepartment(?)', array($id));
            $result = collect($department);
            $response = ['data' => ['department' => $result], 'error' => false, 'message' => 'success'];
            return response()->json($response, 200);
        } catch (\Exception $e) {
            $response = ['data' => [], "error" => true, "message" => $e->getMessage()];
            return response()->json($response, 500);
        }
    }

    public function retrieveDepartments()
    {
        try {
            $department = DB::select('call retrieveDepartments()');
            $result = collect($department);
            $response = ['data' => ['department' => $result], 'error' => false, 'message' => 'success'];
            return response()->json($response, 200);
        } catch (\Exception $e) {
            $response = ['data' => [], "error" => true, "message" => $e->getMessage()];
            return response()->json($response, 500);
        }
    }

}
