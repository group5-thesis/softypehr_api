<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\User;
use Illuminate\Support\Facades\Hash;
use DB;
use App\Models\Result;

class UserController extends Controller
{

    public function createEmployeeAccount($data)
    {
        $user = User::create(
            [
                'username' => $data['username'],
                'password' => Hash::make($data['password']), // default password is Softype@100
                'qr_code' => $data['qr_code'],
                'employeeId' => $data['employeeId']
            ]
        );
    }

    public function retrieveEmployeesAccounts()
    {
        try {
            $employees_accounts = DB::select('call RetrieveEmployeeAccounts()');
            $result = collect($employees_accounts);
            return Result::setData(["employees_accounts" => $result]);
        } catch (\Exception $e) {
            return Result::setError($e->getMessage(), 500);
        }
    }

    public function resetEmployeeAccount(Request $request)
    {
        try {
            DB::beginTransaction();
            $default_password = Hash::make("Softype@100");
            $employee_account = DB::select('call ResetEmployeeAccount(?,?)', array(
                $request->userId, $default_password
            ));
            $result = collect($employee_account);
            $userId = $result[0]->id;
            DB::commit();
            $response = $this->retrieveLimitedEmployeeAccount($userId);
            return $response;
        } catch (\Exception $e) {
            DB::rollback();
            return Result::setError($e->getMessage(), 500);
        }
    }

    public function disableEmployeeAccount(Request $request)
    {
        try {
            DB::beginTransaction();
            $employee_account = DB::select('call DisableEmployeeAccount(?, ?)', array(
                $request->userId, $request->employeeId
            ));
            $result = collect($employee_account);
            $userId = $result[0]->id;
            DB::commit();
            $response = $this->retrieveLimitedEmployeeAccount($userId);
            return $response;
        } catch (\Exception $e) {
            DB::rollback();
            return Result::setError($e->getMessage(), 500);
        }
    }

    public function enableEmployeeAccount(Request $request)
    {
        try {
            DB::beginTransaction();
            $employee_account = DB::select('call EnableEmployeeAccount(?, ?)', array(
                $request->userId, $request->employeeId
            ));
            $result = collect($employee_account);
            $userId = $result[0]->id;
            DB::commit();
            $response = $this->retrieveLimitedEmployeeAccount($userId);
            return $response;
        } catch (\Exception $e) {
            DB::rollback();
            return Result::setError($e->getMessage(), 500);
        }
    }

    public function retrieveLimitedEmployeeAccount($id)
    {
        try {
            $employee_account = DB::select('call RetrieveLimitedEmployeeAccount(?)', array($id));
            $result = collect($employee_account);
            return Result::setData(["employee_account" => $result]);
        } catch (\Exception $e) {
            return Result::setError($e->getMessage(), 500);
        }
    }
}
