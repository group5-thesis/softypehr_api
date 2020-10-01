<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ticket;
use Illuminate\Support\Facades\Validator;
use DB;


class TicketController extends Controller
{
    public function createTicket(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'employeeId' => 'required',
            'title' => 'required',
            'item' => 'required',
            'quantity' => 'required',
            'status' => 'required'
        ]);

        if ($validator->fails()) {
            $messages = $validator->messages();
            $response = ['error' => true, 'message' => $messages];
            return response()->json($response, 401);
        } else {
            DB::beginTransaction();
            try {
                $ticket = DB::select(
                    'call createTicket(?, ?, ?, ?, ?)',
                    array(
                        $request->employeeId, $request->title, $request->item, $request->quantity, $request->status
                    )
                );
                $response = $this->retrieveLimitedTicket($ticket[0]->id);
                DB::commit();
                return $response;
            } catch (\Exception $e) {
                DB::rollback();
                $response = ['data' => $e, 'error' => true, 'message' => $e->getMessage()];
                return response()->json($response, 401);
            }
        }
    }

    public function retrieveLimitedTicket($id)
    {
        try {
            $retrieveTicket = DB::select('call retrieveLimitedTicket(?)', array($id));

            $result = collect($retrieveTicket);

            $response = ['data' => ['ticket_information' => $result], 'error' => false, 'message' => 'success'];
            return response()->json($response, 200);
        } catch (\Exception $e) {
            $response = ['data' => $e, 'error' => true, 'message' => $e->getMessage()];
            return response()->json($response, 401);
        }
    }

    public function updateTicket(Request $request)
    {
        DB::beginTransaction();
        try {
            $ticket = DB::select(
                'call updateTicket(?,?,?,?,?,?)',
                array($request->ticketId, $request->employeeId, $request->title, $request->item, $request->quantity, $request->status)
            );
            $response = $this->retrieveLimitedTicket($request->ticketId);
            DB::commit();
            return $response;
        } catch (\Exception $e) {
            DB::rollback();
            $response = ['data' => $e, 'error' => true, 'message' => $e->getMessage()];
            return response()->json($response, 401);
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
            return response()->json($response, 401);
        }
    }

    public function retrieveTickets()
    {
        try {
            $retrieveTicket = DB::select('call retrieveTickets()');
            $result = collect($retrieveTicket);
            $response = ['data' => ['ticket_information' => $result], 'error' => false, 'message' => 'success'];
            return response()->json($response, 200);
        } catch (\Exception $e) {
            $response = ['data' => $e, 'error' => true, 'message' => $e->getMessage()];
            return response()->json($response, 401);
        }
    }

    public function retrievesTicketsByDate()
    {
        try {
            $ticket_date = DB::select('call retrieveTicketsByDate()');
            $result = collect($ticket_date);
            $response = ['data' => ['ticket_information' => $result], 'error' => false, 'message' => 'success'];
            return response()->json($response, 200);
        } catch (\Exception $e) {
            $response = ['data' => $e, 'error' => true, 'message' => $e->getMessage()];
            return response()->json($response, 401);
        }
    }

    public function retrievesTicketsByMonth($month)
    {
        try {
            $ticket_month = DB::select('call retrieveTicketsByMonth(?)', array($month));
            $result = collect($ticket_month);
            $response = ['data' => ['ticket_information' => $result], 'error' => false, 'message' => 'success'];
            return response()->json($response, 200);
        } catch (\Exception $e) {
            $response = ['data' => $e, 'error' => true, 'message' => $e->getMessage()];
            return response()->json($response, 401);
        }
    }

    public function retrievesTicketsByYear($year)
    {
        try {
            $ticket_year = DB::select('call retrieveTicketsByYear(?)', array($year));
            $result = collect($ticket_year);
            $response = ['data' => ['ticket_information' => $result], 'error' => false, 'message' => 'success'];
            return response()->json($response, 200);
        } catch (\Exception $e) {
            $response = ['data' => $e, 'error' => true, 'message' => $e->getMessage()];
            return response()->json($response, 401);
        }
    }

    public function approveTicket(Request $request)
    {
        DB::beginTransaction();
        try {
            $approved_ticket = DB::select(
                'call approveTicket(?,?,?)',
                array($request->ticketId, $request->employeeId, $request->remarks)
            );
            DB::commit();
            $result = collect($approved_ticket);
            $response = ['data' => ['ticket_information' => $result], 'error' => false, 'message' => 'success'];
            return response()->json($response, 200);
        } catch (\Exception $e) {
            DB::rollback();
            $response = ['data' => $e, 'error' => true, 'message' => $e->getMessage()];
            return response()->json($response, 401);
        }
    }

}
