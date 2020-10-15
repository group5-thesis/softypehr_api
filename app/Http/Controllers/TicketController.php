<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ticket;
use Illuminate\Support\Facades\Validator;
use App\Helpers\Helpers;
use DB;


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
                $response = ['data' => $e, 'error' => true, 'message' => $e->getMessage()];
                return response()->json($response, 500);
            }
        }
    }

    public function retrieveLimitedTicket($id)
    {
        try {
            $retrieveTicket = DB::select('call RetrieveLimitedTicket(?)', array($id));

            $result = collect($retrieveTicket);

            $response = ['data' => ['ticket_information' => $result], 'error' => false, 'message' => 'success'];
            return response()->json($response, 200);
        } catch (\Exception $e) {
            $response = ['data' => $e, 'error' => true, 'message' => $e->getMessage()];
            return response()->json($response, 500);
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
            $response = $this->retrieveLimitedTicket($request->ticketId);
            DB::commit();
            return $response;
        } catch (\Exception $e) {
            DB::rollback();
            $response = ['data' => $e, 'error' => true, 'message' => $e->getMessage()];
            return response()->json($response, 500);
        }
    }

    public function deleteTicket($id)
    {
        DB::beginTransaction();
        try {
            $ticket = DB::select(
                'call deleteTicket(?)',
                array($id)
            );
            DB::commit();
            $response = $this . retrieveTickets();
            return $response;
        } catch (\Exception $e) {
            DB::rollback();
            $response = ['data' => $e, 'error' => true, 'message' => $e->getMessage()];
            return response()->json($response, 500);
        }
    }

    public function retrieveTickets()
    {
        try {
            $retrieveTicket = DB::select('call RetrieveTickets()');
            $result = collect($retrieveTicket);
            $response = ['data' => ['ticket_information' => $result], 'error' => false, 'message' => 'success'];
            return response()->json($response, 200);
        } catch (\Exception $e) {
            $response = ['data' => $e, 'error' => true, 'message' => $e->getMessage()];
            return response()->json($response, 500);
        }
    }

    public function retrieveTicketsByDate()
    {
        try {
            $ticket_date = DB::select('call RetrieveTicketsByDate()');
            $result = collect($ticket_date);
            $response = ['data' => ['ticket_information' => $result], 'error' => false, 'message' => 'success'];
            return response()->json($response, 200);
        } catch (\Exception $e) {
            $response = ['data' => $e, 'error' => true, 'message' => $e->getMessage()];
            return response()->json($response, 500);
        }
    }

    public function retrieveTicketsByMonth($month)
    {
        try {
            $ticket_month = DB::select('call RetrieveTicketsByMonth(?)', array($month));
            $result = collect($ticket_month);
            $response = ['data' => ['ticket_information' => $result], 'error' => false, 'message' => 'success'];
            return response()->json($response, 200);
        } catch (\Exception $e) {
            $response = ['data' => $e, 'error' => true, 'message' => $e->getMessage()];
            return response()->json($response, 500);
        }
    }

    public function retrieveTicketsByStatus($status)
    {
        try {
            $ticket = DB::select('call RetrieveTicketsByStatus(?)', array($status));
            $result = collect($ticket);
            $response = ['data' => ['ticket_information' => $result], 'error' => false, 'message' => 'success'];
            return response()->json($response, 200);
        } catch (\Exception $e) {
            $response = ['data' => $e, 'error' => true, 'message' => $e->getMessage()];
            return response()->json($response, 500);
        }
    }

    public function retrievesTicketsByYear($year)
    {
        try {
            $ticket_year = DB::select('call RetrieveTicketsByYear(?)', array($year));
            $result = collect($ticket_year);
            $response = ['data' => ['ticket_information' => $result], 'error' => false, 'message' => 'success'];
            return response()->json($response, 200);
        } catch (\Exception $e) {
            $response = ['data' => $e, 'error' => true, 'message' => $e->getMessage()];
            return response()->json($response, 500);
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
            // $response = ['data' => ['ticket_information' => $result], 'error' => false, 'message' => 'success'];
            return $response;
        } catch (\Exception $e) {
            DB::rollback();
            $response = ['data' => $e, 'error' => true, 'message' => $e->getMessage()];
            return response()->json($response, 500);
        }
    }

    public function retrieveTicketsByEmployee($id)
    {
        try {
            $employee_tickets = DB::select('call RetrieveTicketsByEmployee(?)', array($id));
            $result = collect($employee_tickets);
            $response = ['data' => ['employee_ticket_information' => $result], 'error' => false, 'message' => 'success'];
            return response()->json($response, 200);
        } catch (\Exception $e) {
            $response = ['data' => $e, 'error' => true, 'message' => $e->getMessage()];
            return response()->json($response, 500);
        }
    }

}
