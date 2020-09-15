<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FormRequest;

class FormRequestController extends Controller
{
    public function createFormRequest(Request $request)
    {
        $form_request = FormRequest::create(
            [
                'employeeId' => $request->employeeId,
                'name' => $request->name,
                'item' => $request->item,
                'quantity' => $request->quantity,
                'status' => $request->status,
                'resolve_date' => $request->resolve_date
            ]
        );
        return response()->json($form_request, 200);
    }

    public function retrieveFormRequests()
    {
        $form_request = FormRequest::get();
        return response()->json($form_request, 200);
    }

    public function retrieveFormRequest_Limited(Request $request)
    {
        $form_request = FormRequest::where('id', '=', $request->id)->get();
        return response()->json($form_request, 200);
    }

    public function deleteFormRequest(Request $request)
    {
        $form_request = FormRequest::where('id', '=', $request->id)->delete();
        return response()->json($form_request, Response::HTTP_OK);
    }
}
