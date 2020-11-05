<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\LeaveRequest;
use App\Models\Result;
use DB;

class LeaveRequestController extends Controller
{
    private $error_message = "Something went wrong.";
    public function createLeaveRequest(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'employeeID' => 'required',
            'date_from' => 'required',
            'date_to' => 'required',
            'reason' => 'required',
            'category' => 'required',
            'approverId' => 'required',
        ]);

        if ($validator->fails()) {
            $messages = json_encode($validator->messages());
            return Result::setError('' , $messages , 401);
        } else {
            try {
                $params = array(
                    $request->employeeID,
                    $request->category,
                    $request->date_from,
                    $request->date_to,
                    $request->reason,
                    $request->approverId,
                );
                $leave_request = DB::select("call UserCreateLeaveRequest(?,?,?,?,?,?)", $params);
                return Result::setData($leave_request);// response()->json(["data" => $leave_request, "error" => false, "message" => "ok"], 200);
            } catch (\Exception $e) {
                return  Result::setError($e->getMessage()) ;//response()->json(["data" => $e, "error" => true, "message" =>$error_message], 500);
            }

        }
    }


    public function retrieveLeaveRequest_Limited(Request $request)
    {
        try{
            $leave_request = LeaveRequest::where('id', '=', $request->id)->get();
            return  Result::setData($leave_request); //response()->json($leave_request, 200);
        }catch(\Exception $e){
            return  Result::setError($e->getMessage());
        }
    }

    public function deleteLeaveRequest(Request $request)
    {
        try{ 
            $leave_request = LeaveRequest::where('id', '=', $request->id)->delete();
            return  Result::setData($leave_request); 
        }catch(\Exception $e){
            return  Result::setError($e->getMessage());
        }
     
    }
    public function getLeaveRequests(Request $request)
    {
        // return [];
        try{
            $employeeId = $request->employeeId;
            $roleId = $request->roleId;
            $leave_request = DB::select("call RetrieveLeaveRequests(?,?,?)", [$roleId, $employeeId ,'pending'] );
            return Result::setData($leave_request);
        }catch(\Exception $e){
            return  Result::setError( $e->getMessage());
        }
     
        
        // $leave_request = LeaveRequest::get();
    }
}
