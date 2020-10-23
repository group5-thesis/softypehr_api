<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\Result;
use DB;

class PerformanceReviewController extends Controller
{
    //
    public function createPerformanceReview(Request $request)
    {
        /**
        *  "date_reviewed",
        *"criteria",
        *"employee_reviewed",
        *"reviewer",
        *"rating"
         */
        $validator = Validator::make($request->all(), [
            'criteria' => 'required',
            'employee_reviewed' => 'required',
            'reviewer' => 'required',
            'rating' => 'required'
        ]);
        if ($validator->fails()) {
            $messages = json_encode($validator->messages());
            $errors = $validator->errors();
            $response = ['data' => $errors->all(), 'error' => true, 'message' => $messages];
            return Result::setError($messages,401);
        } else {
            DB::beginTransaction();
            try {
                $performance_review = DB::select(
                    'call CreatePerformanceReview(?,?,?,?)',
                    array(
                        $request->criteria, $request->employee_reviewed,
                        $request->reviewer, $request->rating
                    ));
                $result = collect($performance_review);
                $performance_review_id = $result[0]->id;

                DB::commit();
                $response = $this->retrieveLimitedPerformanceReview($performance_review_id);
                return $response;
            }catch(\Exception $e){
                DB::rollBack();
                return Result::setError( $e->getMessage() , 500) ;
            }
        }
    }

    public function retrievePerformanceReviews()
    {
        try {
            $performance_reviews = DB::select('call RetrievePerformanceReviews()');
            $result = collect($performance_reviews);
            return Result::setData(["performance_review_information"=>$result]);
        }catch(\Exception $e){
            return Result::setError("Something went wrong", 500);
        }
    }

    public function retrieveLimitedPerformanceReview($id)
    {
        try{
            $performance_review = DB::select(
                'call RetrieveLimitedPerformanceReview(?)',
                array($id)
            );
            $result = collect($performance_review);
            return Result::setData(["performance_review_information" => $result]);
        }catch(\Exception $e){
            return Result::setError("Something went wrong", 500);
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
        }catch(\Exception $e){
            return Result::setError("Something went wrong", 500);
        }
    }
}
