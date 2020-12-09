<?php

namespace App\Http\Controllers;

use App\Http\Controllers\MailController;
use App\Models\LeaveRequest;
use App\Models\Result;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class LeaveRequestController extends Controller
{

    protected $payload = [
        "receiver" => "",
        "name" => "",
        "approver" => "",
        "status" => "",
        "forwarded" => false,
    ];
    protected $mailController;
    private $error_message = "Something went wrong.";
    public function __construct(Type $var = null)
    {
        $this->mailController = new MailController();
    }
    public function createLeaveRequest(Request $request)
    {
        DB::beginTransaction();
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
            return Result::setError('', $messages, 401);
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
                DB::commit();

                $query = DB::select('select email  from employee where id = ?', [$request->approverId]);
                $result = collect($query);
                // get details
                $this->payload['receiver'] = $result[0]->email;
                $this->payload['name'] = collect($leave_request)[0]->name;
                // send email
                $this->mailController->SendEmailNotification("NEW_LEAVE_REQUEST", $this->payload);
                MailController::sendPushNotification('NewLeaveRequestNotification' , [
                    'approver'=>$request->approverId,
                    'employeeId'=>$request->employeeID
                ]);
                return Result::setData($leave_request);
            } catch (\Exception $e) {
                DB::rollback();
                return Result::setError($e->getMessage()); //response()->json(["data" => $e, "error" => true, "message" =>$error_message], 500);
            }

        }
    }

    public function retrieveLeaveRequest_Limited(Request $request)
    {
        try {
            $leave_request = LeaveRequest::where('id', '=', $request->id)->get();
            return Result::setData($leave_request); //response()->json($leave_request, 200);
        } catch (\Exception $e) {
            return Result::setError($e->getMessage());
        }
    }

    public function updateLeaveRequest(Request $request)
    {
        DB::beginTransaction();
        try {
            $id = $request->id;
            $status = $request->status;
            $approver = $request->approver;
            $noOfDays = $request->noOfDays;
            $query = DB::select('call updateLeaveRequest(?,?,?,?)', [$id, $status, $approver, $noOfDays]);
            $result = collect($query);
            if ($result[0]->success == 0) {
                DB::rollback();
                return Result::setError("", $result[0]->message);
            }
            $this->payload['status'] = $status;
            // get requestor name , email,  approver name
            // for requestor name and email
            $employeeDetails = collect(DB::select('select email , concat(firstname , " " , lastname)  as name from employee where id', [$request->employeeId]))[0];
            $approverDetails = collect(DB::select('select  concat(firstname , " " , lastname)  as name from employee where id', [$request->approver]))[0];
            $this->payload["receiver"] = $employeeDetails->email ;
            $this->payload["name"] = $employeeDetails->name ;
            $this->payload["approver"] = $approverDetails->name ;
            $this->payload["status"] = $request->status ;
            $this->mailController->SendEmailNotification("RESOLVED_LEAVE_REQUEST", $this->payload);
            DB::commit();
            MailController::sendPushNotification('UpdateLeaveRequestNotification' , [
                'approver'=>$approver,
                'employeeId'=>$request->employeeId
            ]);
            return Result::setData(["success" => $result[0]->success]);
        } catch (\Exception $e) {
            DB::rollback();
            return Result::setError($e->getMessage());
        }
    }

    public function cancelLeaveRequest(Request $request)
    {
        DB::beginTransaction();
        try {
            $id = $request->id;
            $employeeDetails = collect(DB::select('select employeeId , approver from leave_request where id', [$id]))[0];
            
            $query = DB::select('call deleteLeaveRequest(?)', [$id]);
            $result = collect($query);
            DB::commit();
            MailController::sendPushNotification('CancelledLeaveRequestNotification' ,[
                "approver"=>$employeeDetails->approver,
                "employeeId"=>$employeeDetails->employeeId,
            ]);
            return Result::setData(["success" => $result[0]->success]);
        } catch (\Exception $e) {
            DB::rollback();
            return Result::setError($e->getMessage());
        }

    }
    public function getLeaveRequests(Request $request)
    {
        try {
            $employeeId = $request->employeeId;
            $roleId = $request->roleId;
            $leave_request = DB::select("call RetrieveLeaveRequests(?,?,?)", [$roleId, $employeeId, 'pending']);
            return Result::setData($leave_request);
        } catch (\Exception $e) {
            return Result::setError($e->getMessage());
        }
    }

    public function checkRemainingLeave($empID)
    {
        try {
            $query = DB::select('select remaining_leave from softype.employee where id = ?', [$empID]);
            $results = collect($query);
            return Result::setData(["remaining_leave" => $results[0]->remaining_leave]);
        } catch (\Exception $e) {
            return Result::setError($e->getMessage());
        }

    }

    public function getApprovedRequests(Request $request)
    {
        try {
            $query = DB::select("call GetApprovedRequests()");
            $results = collect($query);
            return Result::setData(["leave_requests" => $results]);
        } catch (\Exception $e) {
            return Result::setError($e->getMessage());
        }
    }
    public function filterLeaveRequest(Request $request)
    {
        $filter = [
            $request->month,
            $request->year,
            $request->status,
            $request->category,
            $request->employeeId,
            $request->roleId,
        ];
        try {
            $query = DB::select("call FilterLeaveRequest(?,?,?,?,?,?)", $filter);
            $results = collect($query);
            return Result::setData(["leave_requests" => $results]);
        } catch (\Exception $e) {
            return Result::setError($e->getMessage());
        }
    }
}
