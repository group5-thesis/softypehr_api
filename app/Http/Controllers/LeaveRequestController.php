<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LeaveRequest;

class LeaveRequestController extends Controller
{
    public function createLeaveRequest(Request $request)
    {
        $leave_request = LeaveRequest::create(
            [
                'employeeId' => $request->employeeId,
                'date_from' => $request->date_from,
                'date_to' => $request->date_to,
                'reason' => $request->reason,
                'type' => $request->type,
                'status' => $request->status,
                'approver' => $request->approver,
                'date_approved' => $request->date_approved,
                'remarks' => $request->remarks,
            ]
        );
        return response()->json($leave_request, 200);
    }

    public function retrieveLeaveRequests()
    {
        $leave_request = LeaveRequest::get();
        return response()->json($leave_request, 200);
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
}
