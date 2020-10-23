<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\DepartmentHeadController;
use App\Models\Result;

class DepartmentController extends Controller
{

    public function addDepartment(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
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
                $department = DB::select(
                    'call AddDepartment(?)',
                    array($request->name)
                );
                $result = collect($department);
                $departmentId = $result[0]->id;
                DepartmentHeadController::addDepartmentHead($departmentId, $request->department_head);
                DB::commit();
                $response = $this->retrieveLimitedDepartment($departmentId);
                return $response;
            } catch (\Exception $e) {
                DB::rollback();
                return Result::setError("Something went wrong", 500);
            }
        }
    }

    public function deleteDepartment(Request $request)
    {
        DB::beginTransaction();
        try {
            $department = DB::select('call DeleteDepartment(?)', array($request->id));
            $response = ['error' => false, 'message' => 'success'];
            DB::commit();
            return Result::setData($response);
        } catch (\Exception $e) {
            DB::rollback();
            return Result::setError("Something went wrong", 500);
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
            $result = collect($department);
            $department_id = $result[0]->id;
            DB::commit();
            $response = $this->retrieveLimitedDepartment($department_id);
            return $response;
        } catch (\Exception $e) {
            DB::rollback();
            return Result::setError("Something went wrong", 500);
        }
    }

    public function retrieveLimitedDepartment($id)
    {
        try {
            $department = DB::select('call RetrieveLimitedDepartment(?)', array($id));
            $result = collect($department);
            return Result::setData(["department" => $result]);
        } catch (\Exception $e) {
            return Result::setError("Something went wrong", 500);
        }
    }

    public function retrieveDepartments()
    {
        try {
            $department = DB::select('call RetrieveDepartments()');
            $result = collect($department);
            return Result::setData(["departments" => $result]);
        } catch (\Exception $e) {
            return Result::setError("Something went wrong", 500);
        }
    }

    public function retrieveDepartmentHeads()
    {
        try {
            $department_head = DB::select('call RetrieveDepartmentHeads()');
            $result = collect($department_head);
            return Result::setData(["department_heads" => $result]);
        } catch (\Exception $e) {
            return Result::setError("Something went wrong", 500);
        }
    }

    public function retrieveDepartmentManagers()
    {
        try {
            $department_managers = DB::select('call RetrieveDepartmentManagers()');
            $result = collect($department_managers);
            return Result::setData(["department_managers" => $result]);
        } catch (\Exception $e) {
            return Result::setError("Something went wrong", 500);
        }
    }
}
