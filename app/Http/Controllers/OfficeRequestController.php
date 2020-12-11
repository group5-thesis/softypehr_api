<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\OfficeRequest;
use Illuminate\Support\Facades\Validator;
use App\Helpers\Helpers;
use DB;
use App\Models\Result;
use App\Http\Controllers\MailController;


class OfficeRequestController extends Controller
{
    protected $mailController;
    public function __construct(Type $var = null)
    {
        $this->mailController = new MailController();
    }
    protected $payload = [
        "receiver" => "",
        "name" => "",
        "approver" => "",
        "status" => "",
        "forwarded" => false,
    ];
    public function createOfficeRequest(Request $request) // SP need to update
    {
        $validator = Validator::make($request->all(), [
            'employeeId' => 'required',
            'item' => 'required',
            'quantity' => 'required',
            'price' => 'required',
            'total_price' => 'required',
            'purpose' => 'required',
            'date_needed' => 'required'
        ]);

        if ($validator->fails()) {
            $messages = $validator->messages();
            $errors = $validator->errors();
            $response = ['data' => $errors->all(), 'error' => true, 'message' => $messages];
            return response()->json($response, 401);
        } else {
            DB::beginTransaction();
            try {
                $officeRequest = DB::select(
                    'call CreateOfficeRequest(?,?,?,?,?,?,?,?)',
                    array(
                        Helpers::createTransactionNo("SOFTYPETKT" . $request->employeeId . "_"),
                        $request->employeeId,
                        $request->item,
                        $request->quantity,
                        $request->price,
                        $request->total_price,
                        $request->date_needed,
                        $request->purpose
                    )
                );
                $response = $this->retrieveLimitedOfficeRequest($officeRequest[0]->id);
                MailController::sendPushNotification('NewOfficeRequestNotification',[
                    "employeeId"=> $request->employeeId
                ]);
                DB::commit();
                return  $response;
            }catch(\Exception $e){
                return  Result::setError($e->getMessage());
            }
        }
    }

    public function updateOfficeRequest(Request $request) // SP need to update
    {
        DB::beginTransaction();
        try {
            $officeRequest = DB::select(
                'call UpdateOfficeRequest(?,?,?,?,?,?,?,?,?)',
                array(
                    $request->officeRequestId,
                    $request->employeeId,
                    $request->item,
                    $request->quantity,
                    $request->price,
                    $request->total_price,
                    $request->purpose,
                    $request->date_needed,
                    $request->status
                )
            );
            $result = collect($officeRequest);
            $officeRequest_id = $result[0]->id;
            DB::commit();
            $employeeDetails = collect(DB::select('select email , concat(firstname , " " , lastname)  as name from employee where id', [$request->employeeId]))[0];
            $ticketDetails = collect(DB::select('select  transaction_no from office_request where id', [$request->officeRequestId]))[0];

            $this->payload["receiver"] = $employeeDetails->email;
            $this->payload["name"] = $employeeDetails->name;
            $this->payload["ticketNo"] = $ticketDetails->transaction_no;
            
            $this->mailController->SendEmailNotification("RESOLVED_OFFICE_REQUEST", $this->payload);
            MailController::sendPushNotification('CLosedOfficeRequestNotification' ,[
                "employeeId"=>$request->employeeId
            ]);            
            $response = $this->retrieveLimitedOfficeRequest($request->officeRequest_id);
            return $response;
        } catch (\Exception $e) {
            DB::rollback();
            return Result::setError($e->getMessage(), 500);
        }
    }

    public function deleteOfficeRequest(Request $request)
    {
        DB::beginTransaction();
        try {
            $officeRequest = DB::select(
                'call DeletOfficeRequest(?)',
                array($request->id)
            );
            $response = ['error' => false, 'message' => 'success'];
            DB::commit();
            $requestDetails = collect(DB::select('select  employeeId from office_request where id', [$request->id]))[0];
            MailController::sendPushNotification('CLosedOfficeRequestNotification' ,[
                "employeeId"=>$requestDetails->employeeId
            ]); 
            return Result::setData($response);
        } catch (\Exception $e) {
            DB::rollback();
            return Result::setError($e->getMessage(), 500);
        }
    }

    public function retrieveOfficeRequests()
    {
        try {
            $retrieveOfficeRequest = DB::select('call RetrieveOfficeRequests()');
            $result = collect($retrieveOfficeRequest);
            return Result::setData(['officeRequest_information' => $result]);
        } catch (\Exception $e) {
            return  Result::setError($e->getMessage());
        }
    }
    public function filterOfficeRequests(Request $request)
    {
        $payload = [
            $request->status,
            $request->month,
            $request->year,
        ];
        try {
            $retrieveOfficeRequest = DB::select('call FilterOfficeRequests(?,?,?)' ,$payload);
            $result = collect($retrieveOfficeRequest);
            return Result::setData(['officeRequest_information' => $result]);
        } catch (\Exception $e) {
            return  Result::setError($e->getMessage());
        }
    }

    public function retrieveOfficeRequestsByDate()
    {
        try {
            $officeRequest_date = DB::select('call RetrieveOfficeRequestsByDate()');
            $result = collect($officeRequest_date);
            return Result::setData(['officeRequest_information' => $result]);
        } catch (\Exception $e) {
            return Result::setError($e->getMessage(), 500);
        }
    }

    public function retrieveOfficeRequestsByMonth($month)
    {
        try {
            $officeRequest_month = DB::select('call RetrieveOfficeRequestsByMonth(?)', array($month));
            $result = collect($officeRequest_month);
            return Result::setData(['officeRequest_information' => $result]);
        } catch (\Exception $e) {
            return Result::setError($e->getMessage(), 500);
        }
    }

    public function retrieveOfficeRequestsByStatus($status)
    {
        try {
            $officeRequest = DB::select('call RetrieveOfficeRequestsByStatus(?)', array($status));
            $result = collect($officeRequest);
            return Result::setData(['officeRequest_information' => $result]);
        } catch (\Exception $e) {
            return Result::setError($e->getMessage(), 500);
        }
    }

    public function retrievesOfficeRequestsByYear($year)
    {
        try {
            $officeRequest_year = DB::select('call RetrieveOfficeRequestsByYear(?)', array($year));
            $result = collect($officeRequest_year);
            return Result::setData(['officeRequest_information' => $result]);
        } catch (\Exception $e) {
            return Result::setError($e->getMessage(), 500);
        }
    }

    public function closeOfficeRequestRequest(Request $request)
    {
        DB::beginTransaction();
        try {
            $approved_officeRequest = DB::select(
                'call CloseOfficeRequest(?,?,?)',
                array($request->officeRequestId, $request->employeeId, $request->indicator)
            );
            DB::commit();
            $result = collect($approved_officeRequest);
            $response = $this->retrieveLimitedOfficeRequest($result[0]->id);
            return $response;
        } catch (\Exception $e) {
            DB::rollback();
            return Result::setError($e->getMessage(), 500);
        }
    }

    public function retrieveOfficeRequestsByEmployee($id)
    {
        try {
            $employee_officeRequests = DB::select('call RetrieveOfficeRequestsByEmployee(?)', array($id));
            $result = collect($employee_officeRequests);
            return Result::setData(['employee_officeRequest_information' => $result]);
        } catch (\Exception $e) {
            return Result::setError($e->getMessage(), 500);
        }
    }

    public function retrieveLimitedOfficeRequest($id)
    {
        try {
            $retrieveOfficeRequest = DB::select('call RetrieveLimitedOfficeRequest(?)', array($id));
            $result = collect($retrieveOfficeRequest);
            return Result::setData(['officeRequest_information' => $result]);
        } catch (\Exception $e) {
            return Result::setError($e->getMessage(), 500);
        }
    }

}
