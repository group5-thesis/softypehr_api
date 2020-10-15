<?php

namespace App\Helpers;


class Helpers
{

    public static function createTransactionNo($prefix)
    {
        $transact_no = $prefix . time();
        return $transact_no;
    }
}
