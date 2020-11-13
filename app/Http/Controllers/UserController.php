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
            $employees_account = DB::select('call ResetEmployeeAccount()', array());
            $result = collect($employees_account);
            return Result::setData(["employees_account" => $result]);
        } catch (\Exception $e) {
            return Result::setError($e->getMessage(), 500);
        }
    }

    public function disableEmployeeAccount(Request $request)
    {
        try {
            $employees_account = DB::select('call DisableEmployeeAccount()', array());
            $result = collect($employees_account);
            return Result::setData(["employees_account" => $result]);
        } catch (\Exception $e) {
            return Result::setError($e->getMessage(), 500);
        }
    }
}
