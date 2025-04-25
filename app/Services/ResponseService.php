<?php

namespace App\Services;

class ResponseService
{
    /**
     * SuccessService constructor.
     */
    public function __construct() {}

    //
    public static function response($data = null, $message = '傳送成功', $success = true)
    {
        return response()->json([
            'success' => $success,
            'errorCode' => 0,
            'message' => $message,
            'data' => $data,
        ], $success ? 200 : 422);
    }
}
