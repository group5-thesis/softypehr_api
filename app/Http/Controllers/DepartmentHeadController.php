<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DepartmentHeadController extends Controller
{
    // Department Head
    public function addDepartmentHead(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'departmentId' => 'required',
                'employeeId' => 'required'
            ]
        );

        try {
            $department_head = DB::select(
                'call addDepartmentHead(?, ?)',
                array($request->departmentId, $request->employeeId)
            );

        } catch (\Exception $e) {
            // DB::rollBack();
            $response = ['data' => [], "error" => true, "message" => $e->getMessage()];
            return response()->json($response, 500);
        }
    }

    public function updateDepartmentHead()
    {
        try {

        } catch (\Exception $e) {
            $response = ['data' => [], "error" => true, "message" => $e->getMessage()];
            return response()->json($response, 500);
        }
    }

    public function retrieveLimitedDepartmentHead()
    {
        try {

        } catch (\Exception $e) {
            $response = ['data' => [], "error" => true, "message" => $e->getMessage()];
            return response()->json($response, 500);
        }
    }

    public function retrieveDepartmentHeads()
    {
        try {

        } catch (\Exception $e) {
            $response = ['data' => [], "error" => true, "message" => $e->getMessage()];
            return response()->json($response, 500);
        }
    }

    public function deleteDepartmentHead()
    {
        try {

        } catch (\Exception $e) {
            $response = ['data' => [], "error" => true, "message" => $e->getMessage()];
            return response()->json($response, 500);
        }
    }


}
