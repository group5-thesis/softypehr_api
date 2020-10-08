<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DepartmentHeadController extends Controller
{
    public function addDepartmentHead(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'departmentId' => 'required',
            'department_head' => 'required'
        ]);

        if ($validator->fails()) {
            $messages = json_encode($validator->messages());
            $errors = $validator->errors();
            $response = ['data' => $errors->all(), 'error' => true, 'message' => $messages];
            return response()->json($response, 400);
        } else {
            DB::beginTransaction();
            try {
                $department_head = DB::select('call AddDepartmentHead(?,?)', array($request->departmentId, $request->department_head));
                $result = collect($department_head);
                $department_head_id = $result[0]->id;
                DB::commit();
            } catch (\Exception $e) {
                DB::rollback();
                $response = ['data' => $e, "error" => true, "message" => $e->getMessage()];
                return response()->json($response, 500);
            }
        }
    }

    public function updateDepartmentHead(Request $request)
    {
        DB::beginTransaction();
        try {
            $updated_department_head = DB::select(
                'call UpdateDepartmentHead(?,?,?)',
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

    public function deleteDepartmentHead($id)
    {
        DB::beginTransaction();
        try {
            $delete_department_head = DB::select(
                'call DeleteDepartmentHead(?)',
                array($request->id)
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

    public function retrieveLimitedDepartmentHead($id)
    {
        try {
            $department_head = DB::select(
                'call RetrieveLimitedDepartmentHead(?)',
                array($request->id)
            );
            $result = collect($department_head);
            $response = ['data' => ['department_head_information' => $result], 'error' => false, 'message' => 'success'];
            return response()->json($response, 200);
        } catch (\Exception $e) {
            $response = ['data' => $e, "error" => true, "message" => $e->getMessage()];
            return response()->json($response, 500);
        }
    }

    public function retrieveDepartmentHeads()
    {
        try {
            $department_heads = DB::select(
                'call RetrieveDepartmentHeads()'
            );
            $result = collect($department_heads);
            $response = ['data' => ['department_head_information' => $result], 'error' => false, 'message' => 'success'];
            return response()->json($response, 200);
        } catch (\Exception $e) {
            $response = ['data' => $e, "error" => true, "message" => $e->getMessage()];
            return response()->json($response, 500);
        }
    }
}
