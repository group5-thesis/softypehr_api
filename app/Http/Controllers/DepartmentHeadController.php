<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use DB;
use App\Models\Result;

class DepartmentHeadController extends Controller
{
    public static function addDepartmentHead($department_id, $department_headId)
    {
        DB::beginTransaction();
        try {
            $department_head = DB::select('call AddDepartmentHead(?,?)', array($department_id, $department_headId));
            $result = collect($department_head);
            $department_head_id = $result[0]->id;
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            return Result::setError( "Something went wrong" , 500) ;
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

            $result = collect($updated_department_head);
            $employee_id = $result[0]->id;
            DB::commit();
            $response = $this->retrieveLimitedDepartmentHead($employee_id);
            return $response;
        } catch (\Exception $e) {
            DB::rollback();
            return Result::setError( "Something went wrong" , 500) ;
        }
    }

    public function deleteDepartmentHead(Request $request)
    {
        DB::beginTransaction();
        try {
            $delete_department_head = DB::select(
                'call DeleteDepartmentHead(?)',
                array($request->id)
            );
            $response = ['error' => false, 'message' => 'success'];
            DB::commit();
            return Result::setData($response);
        } catch (\Exception $e) {
            DB::rollback();
            return Result::setError( "Something went wrong" , 500) ;
        }
    }

    public function retrieveLimitedDepartmentHead($id)
    {
        try {
            $department_head = DB::select(
                'call RetrieveLimitedDepartmentHead(?)',
                array($id)
            );
            $result = collect($department_head);
            return Result::setData(["department_head_information" => $result]);
        } catch (\Exception $e) {
            return Result::setError( "Something went wrong" , 500) ;
        }
    }

    public function retrieveDepartmentHeads()
    {
        try {
            $department_heads = DB::select(
                'call RetrieveDepartmentHeads()'
            );
            $result = collect($department_heads);
            return Result::setData(["department_head_information" => $result]);
        } catch (\Exception $e) {
            return Result::setError( "Something went wrong" , 500) ;
        }
    }
}
