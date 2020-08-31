<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

// User
Route::post('/create', 'UserController@createUser');

// Employee
// Route::post('/create_employee', 'EmployeeController@createEmployee');
// Route::post('/update_employee', 'EmployeeController@updateEmployee');
// Route::post('/retrieve_employee', 'EmployeeController@retrieveEmployees');
// Route::post('/delete_employee' , 'EmployeeController@deleteEmployee');

// // Employee Leave 
// Route::post('/create_request_leave', 'EmployeeLeaveController@createLeave');
// Route::post('/retrieve_remaining_leave', 'EmployeeLeaveController@retrieveRemainingLeave');
// Route::post('/retrieve_employees_on_leave', 'EmployeeLeaveController@retrieveEmployeesOnLeave');
// Route::post('/retrieve_employees_request_leave', 'EmployeeLeaveController@retrieveEmployeesRequestLeave');

// // Employee Duty
// Route::post('/retrieve_employees_on_duty', 'EmployeeDutyController@retrieveEmployeesOnDuty');

// // Organizational Chart
// Route::post('/retrieve_organizational_chart', 'OrganiztionController@retrieveOrganizationalChart');

// // Company Policy
// Route::post('/retrieve_company_policy', 'CompanyController@retrieveCompanyPolicy');
// Route::post('/create_company_policy', 'CompanyController@createCompanyPolicy');

// // Time Keeping
// Route::post('/create_time_in', 'TimeKeepingController@createTimeIn');
// Route::post('/create_time_out', 'TimeKeepingController@createTimeOut');
// Route::post('/retrieve_time_keep_summary', 'TimeKeepingController@retrieveTimeKeepSummary');

// // Employee Requisition
// Route::post('/create_requisition_form', 'EmployeeRequisitionController@createRequisitionForm');
// Route::post('/retrieve_requisition_forms', 'EmployeeRequisitionController@retrieveRequisitionForms');



