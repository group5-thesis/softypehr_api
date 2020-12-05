<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\Result;
use DB;
use App\Http\Controllers\MailController;


class PerformanceReviewController extends Controller
{
    public function createPerformanceReview(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'c1' => 'required',
            'c2' => 'required',
            'c3' => 'required',
            'c4' => 'required',
            'c5' => 'required',
            'employee_reviewed' => 'required',
            'reviewer' => 'required'
        ]);
        if ($validator->fails()) {
            $messages = json_encode($validator->messages());
            $errors = $validator->errors();
            $response = ['data' => $errors->all(), 'error' => true, 'message' => $messages];
            return Result::setError($messages, 401);
        } else {
            DB::beginTransaction();
            try {
                $performance_review = DB::select(
                    'call CreatePerformanceReview(?,?,?,?,?,?,?)',
                    array(
                        $request->c1,
                        $request->c2,
                        $request->c3,
                        $request->c4,
                        $request->c5,
                        $request->employee_reviewed,
                        $request->reviewer
                    )
                );

                $result = collect($performance_review);
                $performance_review_id = $result[0]->id;
                DB::commit();
                MailController::sendPushNotification('EmployeeUpdateNotification');
                $response = $this->retrieveLimitedPerformanceReview($performance_review_id);
                return $response;
            } catch (\Exception $e) {
                DB::rollBack();
                return Result::setError($e->getMessage());
            }
        }
    }

    public function retrievePerformanceReviews()
    {
        try {
            $performance_reviews = DB::select('call RetrieveEmployeePerformanceReviews()');
            $result = collect($performance_reviews);
            return Result::setData(["performance_review_information" => $result]);
        } catch (\Exception $e) {
             return Result::setError($e->getMessage());
        }
    }

    public function retrieveLimitedPerformanceReview($id)
    {
        try {
            $performance_review = DB::select(
                'call RetrieveLimitedPerformanceReview(?)',
                array($id)
            );
            $result = collect($performance_review);
            return Result::setData(["performance_review_information" => $result]);
        } catch (\Exception $e) {
             return Result::setError($e->getMessage());
        }
    }


    public function retrieveLimitedPerformanceReviewByEmployee($id)
    {
        try {
            $performance_review = DB::select(
                'call RetrievePerformanceReviewByEmployee(?)',
                array($id)
            );
            $result = collect($performance_review);
            return Result::setData(["performance_review_information" => $result]);
        } catch (\Exception $e) {
             return Result::setError($e->getMessage());
        }
    }

    public function retrieveEmployeePerformanceReviewByMonth(Request $request)
    {
        try {
            $employe_performance = DB::select(
                "call RetrieveEmployeePerformanceReviewByMonth(?,?)",
                array($request->employee_reviewed, $request->date_reviewed)
            );
            $result = collect($employe_performance);
            return Result::setData(["performance_review_information" => $result]);
        } catch (\Exception $e) {
             return Result::setError($e->getMessage());
        }
    }

    public function retrievePerformanceReviewByMonth()
    {
        try {
            $employe_performance = DB::select(
                "call RetrievePerformanceReviewByMonth()"
            );
            $result = collect($employe_performance);
            return Result::setData(["performance_review_information" => $result]);
        } catch (\Exception $e) {
             return Result::setError($e->getMessage());
        }
    }

    public function retrievePerformanceReviewByYear()
    {
        try {
            $employe_performance = DB::select(
                "call RetrievePerformanceReviewByYear()"
            );
            $result = collect($employe_performance);
            return Result::setData(["performance_review_information" => $result]);
        } catch (\Exception $e) {
             return Result::setError($e->getMessage());
        }
    }

    public function retrievePerformanceReviewByReviewer(Request $request)
    {
        try {
            $employe_performance = DB::select(
                "call RetrievePerformanceReviewByReviewer(?)",
                array($request->reviewer)
            );
            $result = collect($employe_performance);
            return Result::setData(["performance_review_information" => $result]);
        } catch (\Exception $e) {
             return Result::setError($e->getMessage());
        }
    }
}
