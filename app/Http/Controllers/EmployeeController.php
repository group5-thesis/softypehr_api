<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Employee;
use App\Models\Account;
use Illuminate\Support\Str;
use DB;
use Exception;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\FileController;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Hash;
use App\Models\Result;

// use QrCode;

class EmployeeController extends Controller
{
    public function createEmployee(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'firstname' => 'required',
            'lastname' => 'required',
            'mobileno' => 'required',
            'birthdate' => 'required',
            'email' => 'required|unique:employee|email',
            'gender' => 'required',
            'street' => 'required',
            'city' => 'required',
            'country' => 'required',
            'role' => 'required',
        ]);

        if ($validator->fails()) {
            return Result::setError("",$validator->messages()->get('email')[0] ,401);
        } else {
            DB::beginTransaction();
            try {
                $employee = DB::select(
                    'call CreateEmployee(?,?,?,?,?,?,?,?,?,?,?,?,?,?)',
                    array(
                        $request->firstname,
                        $request->middlename,
                        $request->lastname,
                        $request->mobileno,
                        $request->gender,
                        $request->email,
                        $request->birthdate,
                        $request->street,
                        $request->city,
                        $request->country,
                        $request->phil_health_no,
                        $request->sss,
                        $request->pag_ibig_no,
                        $request->role
                    )
                );
                $result = collect($employee);
                $employee_id = $result[0]->id;

                if ($employee) {
                    $firstName = $request->firstname;
                    $lastName = $request->lastname;
                    $username = Str::lower($firstName[0] . $lastName . $employee_id);
                    $defaultPassword = Hash::make('Softype@100');
                    $file = 'qrcode/' . $username . '_' . $employee_id . '.svg';
                    \QrCode::size(250)->format('svg')->generate(json_encode($result[0]), public_path($file));
                    DB::select(
                        'call CreateEmployeeAccount(?,?,?,?,?)',
                        array($username, $defaultPassword, $file, $employee_id, $request->accountType)
                    );
                }
                DB::commit();
                $response = $this->retrieveLimitedEmployee($employee_id);
                return $response;
            } catch (\Exception $e) {
                DB::rollBack();
                return Result::setError($e->getMessage());
            }
        }
    }

    public function retrieveEmployees()
    {
        try {
            $employees = DB::select('call RetrieveEmployees()');
            $result = collect($employees);
            return Result::setData(['employee_information' => $result]);
        } catch (\Exception $e) {
            return Result::setError($e->getMessage());
        }

    }

    public function retrieveLimitedEmployee($id)
    {
        try {
            $employee = DB::select('call RetrieveLimitedEmployee(?)', array($id));
            $result = collect($employee);
            return Result::setData(['employee_information' => $result]);
        } catch (\Exception $e) {

            return Result::setError("Something went wrong", 500);

            return Result::setError($e->getMessage());

        }
    }

    public function retrieveEmployeeByDepartment($id)
    {
        try {
            $employees = DB::select('call RetrieveEmployeeByDepartment(?)', array($id));
            $result = collect($employees);
            return Result::setData(['employee_information' => $result]);
        } catch (\Exception $e) {
            return Result::setError($e->getMessage());
        }
    }

    public function retrieveEmployeeByManager($id)
    {
        try {
            $employees = DB::select('call RetrieveEmployeeByManager(?)', array($id));
            $result = collect($employees);
            return Result::setData(['employee_information' => $result]);
        } catch (\Exception $e) {
            return Result::setError($e->getMessage());
        }
    }

    public function updateEmployee(Request $request)
    {
        try {
            DB::beginTransaction();
            $updated_employee = DB::select(
                'call UpdateEmployee(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)',
                array(
                    $request->employeeId,
                    $request->firstname,
                    $request->middlename,
                    $request->lastname,
                    $request->mobileno,
                    $request->gender,
                    $request->email,
                    $request->birthdate,
                    $request->street,
                    $request->city,
                    $request->country,
                    $request->phil_health_no,
                    $request->sss,
                    $request->pag_ibig_no,
                    $request->isActive,
                    $request->roleId
                )
            );
            $result = collect($updated_employee);
            $employee_id = $result[0]->id;
            $response = $this->retrieveLimitedEmployee($employee_id);
            DB::commit();
            return $response;
        } catch (\Exception $e) {
            DB::rollback();
            return Result::setError($e->getMessage());
        }
    }

    public function deleteEmployee($id)
    {
        try {
            DB::beginTransaction();
            $deleted_employee = DB::select('call DeleteEmployee(?)', array($id));
            $response = ['error' => false, 'message' => 'success'];
            DB::commit();
            return Result::setData($response);
        } catch (\Exception $e) {
            DB::rollback();
            return Result::setError($e->getMessage());
        }
    }

    public function retrieveEmployeeProfile(Request $request)
    {
        try {
            $employee = DB::select('call UserGetProfile(?)', array($request->userId));
            return Result::setData($employee);

        } catch (\Exception $e) {
            return Result::setError($e->getMessage());
        }
    }

    public function updateProfilePicture(Request $request)
    {
        try {
            DB::beginTransaction();
            $file = $request->file;
            $employee_id = $request->employee_id;
            $imageName = FileController::store($file);
            $query = DB::select("call UpdateProfileImage(?,?)", array($employee_id, $imageName));
            $result = collect($query);
            if ($result[0]->completed > 0) {
                DB::commit();
                return $this->retrieveLimitedEmployee($employee_id);
            } else {
                DB::rollback();

                return Result::setError("","Update failed", 400);
            }
        } catch (\Exception $e) {
            return Result::setError($e->getMessage());
        }
    // } catch (\Exception $e) {

    //     return Result::setError($e->getMessage());


    //     return Result::setError($e->getMessage());

        DB::rollback();
    }
// }

}
