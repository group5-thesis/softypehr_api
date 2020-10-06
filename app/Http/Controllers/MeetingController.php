<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Illuminate\Support\Facades\Validator;

class MeetingController extends Controller
{
    public function createMeeting(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'organizer' => 'required',
            'category' => 'required',
            'members' => 'required',
            'set_date' => 'required',
            'time_start' => 'required',
            'time_end' => 'required',
            'status' => 'required'
        ]);

        if ($validator->fails()) {
            $messages = $validator->messages();
            $errors = $validator->errors();
            $response = ['data' => $errors->all(), 'error' => true, 'message' => $messages];
            return response()->json($response, 401);
        } else {
            DB::beginTransaction(); // begin the queries transaction
            try {
                $meeting = DB::select(
                    'call CreateMeeting(?,?,?,?,?,?,?,?)',
                    array(
                        $request->title, $request->organizer, $request->category, $request->description, $request->set_date,
                        $request->time_start, $request->time_end, $request->status
                    )
                );

                $res_meeting = collect($meeting);

                foreach ($request->members as $key => $member) {
                    $members = DB::select(
                        'call AddMember(?,?)',
                        array($res_meeting[0]->id, $member)
                    );
                }

                $response = $this->retrieveLimitedMeeting($res_meeting[0]->id);

                DB::commit(); // save the queries to execution for DB
                return $response;
            } catch (\Exception $e) {
                DB::rollback(); // undo the queries
                $response = ['data' => $e, 'error' => true, 'message' => $e->getMessage()];
                return response()->json($response, 401);
            }
        }
    }

    public function updateMeeting(Request $request)
    {
        DB::beginTransaction();
        try {
            if ($request->addMember) {
                foreach ($request->addMember as $key => $member) {
                    $added_members = DB::select(
                        'call AddMember(?,?)',
                        array($request->meetingId, $member)
                    );
                }
            }

            if ($request->deleteMember) {
                foreach ($request->deleteMember as $key => $member) {
                    $deleted_member = DB::select(
                        'call DeleteMember(?)',
                        array($member)
                    );
                }
            }

            $update_meeting = DB::select(
                'call UpdateMeeting(?,?,?,?,?,?,?,?,?)',
                array(
                    $request->meetingId, $request->title, $request->organizer, $request->category, $request->description,
                    $request->set_date, $request->time_start, $request->time_end, $request->status
                )
            );

            $response = $this->retrieveLimitedMeeting($request->meetingId);

            DB::commit();
            return $response;
        } catch (\Exception $e) {
            DB::rollback();
            $response = ['data' => $e, 'error' => true, 'message' => $e->getMessage()];
            return response()->json($response, 401);
        }
    }

    public function deleteMeeting($id)
    {
        DB::beginTransaction();
        try {
            $delete_meeting = DB::select('call DeleteMeeting(?)', array($id));
            DB::commit();
            $response = ['error' => false, 'message' => 'success'];
            return response()->json($response, 200);
        } catch (\Exception $e) {
            DB::rollback();
            $response = ['data' => $e, 'error' => true, 'message' => $e->getMessage()];
            return response()->json($response, 401);
        }
    }

    public function retrieveMeetings()
    {
        try {
            $meetings = DB::select('call RetrieveMeetings()');
            $result_meeting = collect($meetings);
            $resp = [];

            foreach ($result_meeting as $key => $meeting) {

                $meetingId = $meeting->meetingId;
                $resp[$meetingId]["meetingId"] = $meeting->meetingId;
                $resp[$meetingId]["title"] = $meeting->title;
                $resp[$meetingId]["category"] = $meeting->category;
                $resp[$meetingId]["organizer"] = $meeting->organizer;
                $resp[$meetingId]["description"] = $meeting->description;
                $resp[$meetingId]["set_date"] = $meeting->set_date;
                $resp[$meetingId]["time_start"] = $meeting->time_start;
                $resp[$meetingId]["time_end"] = $meeting->time_end;
                $resp[$meetingId]["status"] = $meeting->status;

                $resp[$meetingId]["members"][] = [
                    "id" => $meeting->memberId, "email" => $meeting->email,
                    "firstname" => $meeting->firstname, "middlename" => $meeting->middlename, "lastname" => $meeting->lastname
                ];
            }
            $response = ['data' => ['meeting_information' => $resp], 'error' => false, 'message' => 'success'];
            return response()->json($response, 200);
        } catch (\Exception $e) {
            $response = ['data' => $e, 'error' => true, 'message' => $e->getMessage()];
            return response()->json($response, 401);
        }
    }

    public function retrieveLimitedMeeting($id)
    {
        try {
            $retrieveMeeting = DB::select('call RetrieveLimitedMeeting(?)', array($id));

            $result_meeting = collect($retrieveMeeting);

            $member = [];
            $resp = [];

            foreach ($result_meeting as $meeting) {
                array_push($member, [
                    "id" => $meeting->memberId, "email" => $meeting->email,
                    "firstname" => $meeting->firstname, "middlename" => $meeting->middlename, "lastname" => $meeting->lastname
                ]);
                $resp = [
                    "meeting id" => $meeting->meetingId,
                    "title" => $meeting->title,
                    "category" => $meeting->category,
                    "organizer" => $meeting->organizer,
                    "description" => $meeting->description,
                    "set_date" => $meeting->set_date,
                    "time_start" => $meeting->time_start,
                    "time_end" => $meeting->time_end,
                    "status" => $meeting->status,
                ];
            }

            $resp["members"] = $member;

            $response = ['data' => ['meeting_information' => $resp], 'error' => false, 'message' => 'success'];
            return response()->json($response, 200);
        } catch (\Exception $e) {
            $response = ['data' => $e, 'error' => true, 'message' => $e->getMessage()];
            return response()->json($response, 401);
        }
    }

    public function retrieveMeetingByCurrentDate()
    {
        try {
            $meeting_now = DB::select('call RetrieveMeetingByCurrentDate()');
            $response = ['data' => ['meeting_information' => $meeting_now], 'error' => false, 'message' => 'success'];
            return response()->json($response, 200);
        } catch (\Exception $e) {
            $response = ['data' => $e, 'error' => true, 'message' => $e->getMessage()];
            return response()->json($response, 401);
        }
    }

}
