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

Route::group(['middleware' => 'api-header'], function () {
    // The registration and login requests doesn't come with tokens
    // as users at that point have not been authenticated yet
    // Therefore the jwtMiddleware will be exclusive of them

    //Leave Request
    Route::post('/create_request_leave', 'LeaveRequestController@createLeaveRequest');
    Route::post('/getLeaveRequest', 'LeaveRequestController@getLeaveRequests');

    // Meeting
    Route::post('/create_meeting', 'MeetingController@createMeeting');
    Route::get('/retrieve_meetings', 'MeetingController@retrieveMeetings');
    Route::get('/retrieve_limited_meeting/{id}', 'MeetingController@retrieveLimitedMeeting');
    Route::post('/update_meeting', 'MeetingController@updateMeeting');
    Route::get('/retrieve_meeting_now', 'MeetingController@retrieveMeetingByCurrentDate');
    Route::post('/delete_meeting/{id}', 'MeetingController@deleteMeeting');

    // OfficeRequest
    Route::post('/create_officeRequest', 'OfficeRequestController@createOfficeRequest');
    Route::post('/update_officeRequest', 'OfficeRequestController@updateOfficeRequest');
    Route::get('/retrieve_officeRequests', 'OfficeRequestController@retrieveOfficeRequests');
    Route::get('/retrieve_officeRequests_by_year/{year}', 'OfficeRequestController@retrieveOfficeRequestsByYear');
    Route::get('/retrieve_officeRequests_by_month/{month}', 'OfficeRequestController@retrieveOfficeRequestsByMonth');
    Route::get('/retrieve_officeRequests_by_date', 'OfficeRequestController@retrieveOfficeRequestsByDate');
    Route::post('/delete_officeRequest', 'OfficeRequestController@deleteOfficeRequest');
    Route::post('/close_officeRequest', 'OfficeRequestController@closeOfficeRequestRequest');
    Route::get('/retrieve_officeRequests_by_status/{status}', 'OfficeRequestController@retrieveOfficeRequestsByStatus');
    Route::get('/retrieve_officeRequests_by_employee/{id}', 'OfficeRequestController@retrieveOfficeRequestsByEmployee');

    // Auth
    Route::post('/login', 'AuthController@login');
    Route::post('/forgotPassword', 'AuthController@forgotPassword');
    Route::post('/changePassword', 'AuthController@changePassword');
    Route::post('/verifyOTP', 'AuthController@verifyOTP');

    // File upload
    Route::get('/image/{folder}/{file}', 'FileController@serve');
    // Route::post('/upload','FileController@store');
    // Route::get('/retrieveLimitedFiles/{id}','FileController@retrieveLimitedFile');
    Route::post('/add_file', 'FileController@addFile');
    Route::get('/retrieve_files', 'FileController@retrieveFiles');
    Route::get('/retrieve_files_by_type/{id}', 'FileController@retrieveFilesByType');
    Route::post('/delete_file/{id}', 'FileController@deleteFile');
    Route::post('/update_file', 'FileController@updateFile');
    Route::get('/image/{filename}', 'FileController@serveImage');


    // Employee
    Route::post('/create_employee', 'EmployeeController@createEmployee');
    Route::get('/retrieve_employees', 'EmployeeController@retrieveEmployees');
    Route::get('/retrieve_limited_employee/{id}', 'EmployeeController@retrieveLimitedEmployee');
    Route::get('/retrieve_employee_by_department/{id}', 'EmployeeController@retrieveEmployeeByDepartment');
    Route::get('/retrieve_employee_by_manager/{id}', 'EmployeeController@retrieveEmployeeByManager');
    Route::post('/delete_employee/{id}', 'EmployeeController@deleteEmployee');
    Route::post('/update_employee', 'EmployeeController@updateEmployee');
    Route::post('/update_profile/img', 'EmployeeController@updateProfilePicture');
    Route::post('/retrieve_employee_profile', 'EmployeeController@retrieveEmployeeProfile');
    Route::post('/retrieve_employee_limited', 'EmployeeController@retrieveEmployeeLimited');
    Route::get('/getProfile', 'EmployeeController@retrieveEmployeeProfile');


    // Department
    Route::post('/add_department', 'DepartmentController@addDepartment'); // in adding the department, dept_head also added
    Route::post('/delete_department', 'DepartmentController@deleteDepartment');
    Route::post('/update_department', 'DepartmentController@updateDepartment');
    Route::get('/retrieve_limited_department/{id}', 'DepartmentController@retrieveLimitedDepartment');
    Route::get('/retrieve_departments', 'DepartmentController@retrieveDepartments');
    Route::get('/retrieve_department_heads_v1', 'DepartmentController@retrieveDepartmentHeads');
    Route::post('/retrieve_departments_managers_v1', 'DepartmentController@retrieveDepartmentManagers');
    Route::post('/retrieve_managers_by_department', 'DepartmentController@retrieveManagersByDepartment');


    // Department Employee
    Route::post('/add_department_employee', 'DepartmentEmployeeController@addDepartmentEmployee');
    Route::post('/delete_department_employee', 'DepartmentEmployeeController@deleteDepartmentEmployee');
    Route::get('/retrieve_limited_department_employee/{id}', 'DepartmentEmployeeController@retrieveLimitedDepartmentEmployee');
    Route::get('/retrieve_department_employees', 'DepartmentEmployeeController@retrieveDepartmentEmployees');
    Route::post('/update_department_employee', 'DepartmentEmployeeController@updateDepartmentEmployee');

    // Department Manager
    Route::post('/add_department_manager', 'DepartmentManagerController@addDepartmentManager');
    Route::get('/retrieve_department_managers', 'DepartmentManagerController@retrieveDepartmentManagers');
    Route::get('/retrieve_limited_department_manager/{id}', 'DepartmentManagerController@retrieveLimitedDepartmentManager');
    Route::post('/update_department_manager', 'DepartmentManagerController@updateDepartmentManager');
    Route::post('/delete_department_manager', 'DepartmentManagerController@deleteDepartmentManager');

    // Department Head
    Route::post('/update_department_head', 'DepartmentHeadController@updateDepartmentHead');
    Route::get('/retrieve_department_heads', 'DepartmentHeadController@retrieveDepartmentHeads');
    Route::get('/retrieve_limited_department_head/{id}', 'DepartmentHeadController@retrieveLimitedDepartmentHead');
    Route::post('/delete_department_head', 'DepartmentHeadController@deleteDepartmentHead');


    // Performance Review
    Route::post('/create_performance_review', 'PerformanceReviewController@createPerformanceReview');
    Route::get('/retrieve_performance_reviews', 'PerformanceReviewController@retrievePerformanceReviews');
    Route::get('/retrieve_performance_review/{id}', 'PerformanceReviewController@retrieveLimitedPerformanceReview');
    Route::get('/retrieve_performance_review_by_employee/{id}', 'PerformanceReviewController@retrieveLimitedPerformanceReviewByEmployee');
    Route::post('/retrieve_performance_review_by_employee_month', 'PerformanceReviewController@retrieveEmployeePerformanceReviewByMonth');

    // Accounts
    Route::get('/retrieve_employees_accounts', 'UserController@retrieveEmployeesAccounts');
    Route::post('/reset_employee_account', 'UserController@resetEmployeeAccount');
    Route::post('/disable_employee_account', 'UserController@disableEmployeeAccount');
    Route::get('/retrieve_limited_employee_account', 'UserController@retrieveLimitedEmployeeAccount');
    Route::post('/enable_employee_account', 'UserController@enableEmployeeAccount');

});

// this routes are needed with jwt token, you need first to login before executing this routes
Route::group(['middleware' => ['jwt.auth', 'api-header']], function () {

     // all routes to protected resources are registered here

    Route::get('sendbasicemail', 'MailController@basic_email');
    Route::get('sendhtmlemail', 'MailController@html_email');
    Route::get('sendattachmentemail', 'MailController@attachment_email');
});
