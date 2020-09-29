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

Route::post('/test', function () {
    return "Test";
});

Route::group(['middleware' => 'api-header'], function () {
    // The registration and login requests doesn't come with tokens
    // as users at that point have not been authenticated yet
    // Therefore the jwtMiddleware will be exclusive of them
    Route::post('/create_meeting','MeetingController@createMeeting');
    Route::get('/retrieve_meetings','MeetingController@retrieveMeetings');
    Route::get('/retrieve_limited_meeting/{id}','MeetingController@retrieveLimitedMeeting');
    Route::post('/update_meeting','MeetingController@updateMeeting');
    Route::get('/retrieve_meeting_now','MeetingController@retrieveMeetingByCurrentDate');
    Route::post('/delete_meeting/{id}','MeetingController@deleteMeeting');
    Route::post('/login', 'AuthController@login');
    Route::get('/image/{folder}/{file}','FileController@serve');
    Route::post('/create_employee', 'EmployeeController@createEmployee');
});


// this routes are needed with jwt token, you need first to login before executing this routes
Route::group(['middleware' => ['jwt.auth', 'api-header']], function () {

     // all routes to protected resources are registered here
     Route::get('/retrieve_users', 'UserController@retrieveUsers');

});

// User
// Route::post('/create_user', 'AccountController@createUser');
// Route::post('/retrieve_user', 'UserController@retrieveUser');
// Route::post('/update_user', 'UserController@updateUser');
// Route::post('/delete_user', 'UserController@deleteUser');

// Employee
Route::post('/retrieve_employee_limited', 'EmployeeController@retrieveEmployeeLimited');
Route::get('/retrieve_employees', 'EmployeeController@retrieveEmployees');
Route::get('/getProfile', 'EmployeeController@retrieveEmployeeProfile');
// Route::post('/update_employee', 'EmployeeController@updateEmployee');
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

Route::get('sendbasicemail','MailController@basic_email');
Route::get('sendhtmlemail','MailController@html_email');
Route::get('sendattachmentemail','MailController@attachment_email');
Route::get('uploadfile', 'PagesController@index'); // localhost:8000/
Route::post('/uploadFile', 'PagesController@uploadFile');


