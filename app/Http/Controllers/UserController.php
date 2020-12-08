<?php

namespace App\Http\Controllers;

use App\Http\Controllers\MailController;
use App\Models\Result;
use App\User;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    protected $mailController;
    protected $payload = [
        "receiver" => "",
        "name" => "",
        "approver" => "",
        "status" => "",
        "forwarded" => false,
    ];
    public function __construct(Type $var = null)
    {
        $this->mailController = new MailController();
    }

    public function createEmployeeAccount($data)
    {
        $user = User::create(
            [
                'username' => $data['username'],
                'password' => Hash::make($data['password']), // default password is Softype@100
                'qr_code' => $data['qr_code'],
                'employeeId' => $data['employeeId'],
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
                $request->userId, $default_password,
            ));
            $result = collect($employee_account);
            $userId = $result[0]->id;
            DB::commit();
            MailController::sendPushNotification('ResetPasswordNotification', [
                "userId" => $userId,
            ]);
            MailController::sendPushNotification('EmployeeUpdateNotification');
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
                $request->userId, $request->employeeId,
            ));
            $result = collect($employee_account);
            $userId = $result[0]->id;
            DB::commit();
            $employee = DB::select('call UserGetProfile(?)', array($userId));
            $res = collect($employee)[0];
            $mailController->sendEmailNotice($res->email);
            MailController::sendPushNotification('ResetPasswordNotification', [
                "userId" => $userId,
            ]);
            MailController::sendPushNotification('EmployeeUpdateNotification');
            $response = $this->retrieveLimitedEmployeeAccount($userId);
            return $response;
        } catch (\Exception $e) {
            DB::rollback();
            return Result::setError($e->getMessage(), 500);
        }
    }

    public function enableEmployeeAccount(Request $request)
    {
        // EmployeeUpdateNotification
        try {
            DB::beginTransaction();
            $employee_account = DB::select('call EnableEmployeeAccount(?, ?)', array(
                $request->userId, $request->employeeId,
            ));
            $result = collect($employee_account);
            $userId = $result[0]->id;
            DB::commit();
            $employee = DB::select('call UserGetProfile(?)', array($userId));
            $res = collect($employee)[0];
            $mailController->sendEmailWelcome($res->email);
            MailController::sendPushNotification('EmployeeUpdateNotification');
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
