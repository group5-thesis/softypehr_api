<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\LeaveRequest;
use DB;
class LeaveRequestController extends Controller
{
    public function createLeaveRequest(Request $request)
    {   
        $validator = Validator::make($request->all(), [
            'employeeID'=>'required',
            'date_from'=>'required',
            'date_to'=>'required',
            'reason'=>'required',
            'category'=>'required',
            'approverId'=>'required',
        ]);

        if ($validator->fails()) {
            $messages =json_encode($validator->messages());
            return response()->json(['data' => null ,'error' => true, 'message' => $messages], 400);
        } else {
            try{
                $params = array(
                    $request->employeeID,
                    $request->category,
                    $request->date_from,
                    $request->date_to,
                    $request->reason,
                    $request->approverId,
                );
                $leave_request =  DB::select("call UserCreateLeaveRequest(?,?,?,?,?,?)" ,$params);
                return response()->json(["data" =>$leave_request , "error"=>false , "message" =>"ok"], 200);
            }catch(\Exception $e){
                return  response()->json(["data"=>$e ,  "error"=>true , "message"=>$e->getMessage() ],500);
            }
          
    }
    }

    
    public function retrieveLeaveRequest_Limited(Request $request)
    {
        $leave_request = LeaveRequest::where('id', '=', $request->id)->get();
        return response()->json($leave_request, 200);
    }
    
    public function deleteLeaveRequest(Request $request)
    {
        $leave_request = LeaveRequest::where('id', '=', $request->id)->delete();
        return response()->json($leave_request, Response::HTTP_OK);
    }
    public function getLeaveRequests(Request $request)
    {
        $employeeId = $request->employeeId;
        $roleId = $request->roleId;
        switch ($roleId) {
            case 1:
               return "admin";
                break;
            case 2:
                # code...
                return "manager";
                break;
            case 3:
                # code...
                return "regular";
                break;
            
            default:
                # code...
                break;
        }
        $leave_request = LeaveRequest::get();
        return response()->json($leave_request, 200);
    }
}
