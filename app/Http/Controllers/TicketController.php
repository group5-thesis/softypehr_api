<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ticket;
use Illuminate\Support\Facades\Validator;
use App\Helpers\Helpers;
use DB;
use App\Models\Result;


class TicketController extends Controller
{
    public function createTicket(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'employeeId' => 'required',
            'description' => 'required',
            'item' => 'required',
            'quantity' => 'required'
        ]);

        if ($validator->fails()) {
            $messages = $validator->messages();
            $errors = $validator->errors();
            $response = ['data' => $errors->all(), 'error' => true, 'message' => $messages];
            return response()->json($response, 401);
        } else {
            DB::beginTransaction();
            try {
                $ticket = DB::select(
                    'call CreateTicket(?,?,?,?,?)',
                    array(
                        Helpers::createTransactionNo("SOFTYPETKT" . $request->employeeId . "_"), $request->employeeId, $request->item, $request->quantity, $request->description
                    )
                );
                $response = $this->retrieveLimitedTicket($ticket[0]->id);
                DB::commit();
                return $response;
            } catch (\Exception $e) {
                DB::rollback();
                return Result::setError("Something went wrong", 500);
            }
        }
    }

    public function retrieveLimitedTicket($id)
    {
        try {
            $retrieveTicket = DB::select('call RetrieveLimitedTicket(?)', array($id));
            $result = collect($retrieveTicket);
            return Result::setData(['ticket_information' => $result]);
        } catch (\Exception $e) {
            return Result::setError("Something went wrong", 500);
        }
    }

    public function updateTicket(Request $request)
    {
        DB::beginTransaction();
        try {
            $ticket = DB::select(
                'call UpdateTicket(?,?,?,?,?,?)',
                array($request->ticketId, $request->employeeId, $request->description, $request->item, $request->quantity, $request->status)
            );
            $result = collect($ticket);
            $ticket_id = $result[0]->id;
            DB::commit();
            $response = $this->retrieveLimitedTicket($request->ticket_id);
            return $response;
        } catch (\Exception $e) {
            DB::rollback();
            return Result::setError("Something went wrong", 500);
        }
    }

    public function deleteTicket(Request $request)
    {
        DB::beginTransaction();
        try {
            $ticket = DB::select(
                'call deleteTicket(?)',
                array($request->id)
            );
            $response = ['error' => false, 'message' => 'success'];
            DB::commit();
            return Result::setData($response);
        } catch (\Exception $e) {
            DB::rollback();
            return Result::setError("Something went wrong", 500);
        }
    }

    public function retrieveTickets()
    {
        try {
            $retrieveTicket = DB::select('call RetrieveTickets()');
            $result = collect($retrieveTicket);
            return Result::setData(['ticket_information' => $result]);
        } catch (\Exception $e) {
            return Result::setError("Something went wrong", 500);
        }
    }

    public function retrieveTicketsByDate()
    {
        try {
            $ticket_date = DB::select('call RetrieveTicketsByDate()');
            $result = collect($ticket_date);
            return Result::setData(['ticket_information' => $result]);
        } catch (\Exception $e) {
            return Result::setError("Something went wrong", 500);
        }
    }

    public function retrieveTicketsByMonth($month)
    {
        try {
            $ticket_month = DB::select('call RetrieveTicketsByMonth(?)', array($month));
            $result = collect($ticket_month);
            return Result::setData(['ticket_information' => $result]);
        } catch (\Exception $e) {
            return Result::setError("Something went wrong", 500);
        }
    }

    public function retrieveTicketsByStatus($status)
    {
        try {
            $ticket = DB::select('call RetrieveTicketsByStatus(?)', array($status));
            $result = collect($ticket);
            return Result::setData(['ticket_information' => $result]);
        } catch (\Exception $e) {
            return Result::setError("Something went wrong", 500);
        }
    }

    public function retrievesTicketsByYear($year)
    {
        try {
            $ticket_year = DB::select('call RetrieveTicketsByYear(?)', array($year));
            $result = collect($ticket_year);
            return Result::setData(['ticket_information' => $result]);
        } catch (\Exception $e) {
            return Result::setError("Something went wrong", 500);
        }
    }

    public function closeTicketRequest(Request $request)
    {
        DB::beginTransaction();
        try {
            $approved_ticket = DB::select(
                'call CloseTicketRequest(?,?,?)',
                array($request->ticketId, $request->employeeId, $request->indicator)
            );
            DB::commit();
            $result = collect($approved_ticket);
            $response = $this->retrieveLimitedTicket($result[0]->id);
            return $response;
        } catch (\Exception $e) {
            DB::rollback();
            return Result::setError("Something went wrong", 500);
        }
    }

    public function retrieveTicketsByEmployee($id)
    {
        try {
            $employee_tickets = DB::select('call RetrieveTicketsByEmployee(?)', array($id));
            $result = collect($employee_tickets);
            return Result::setData(['employee_ticket_information' => $result]);
        } catch (\Exception $e) {
            return Result::setError("Something went wrong", 500);
        }
    }

}
