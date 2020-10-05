<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DepartmentEmployeeController extends Controller
{
    // Department Employee
    public function addDepartmentEmployee()
    {
        try {

        } catch (\Exception $e) {
            $response = ['data' => [], "error" => true, "message" => $e->getMessage()];
            return response()->json($response, 500);
        }
    }

    public function deleteDepartmentEmployee()
    {
        try {

        } catch (\Exception $e) {
            $response = ['data' => [], "error" => true, "message" => $e->getMessage()];
            return response()->json($response, 500);
        }
    }

    public function retrieveLimitedDepartmentEmployee()
    {
        try {

        } catch (\Exception $e) {
            $response = ['data' => [], "error" => true, "message" => $e->getMessage()];
            return response()->json($response, 500);
        }
    }

    public function retrieveDepartmentEmployees()
    {
        try {

        } catch (\Exception $e) {
            $response = ['data' => [], "error" => true, "message" => $e->getMessage()];
            return response()->json($response, 500);
        }
    }

    public function updateDepartmentEmployee()
    {
        try {

        } catch (\Exception $e) {
            $response = ['data' => [], "error" => true, "message" => $e->getMessage()];
            return response()->json($response, 500);
        }
    }
}
