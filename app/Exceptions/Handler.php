<?php

namespace App\Exceptions;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        //
    }

    /**
     * 自定義錯誤回應格式
     */
    public function render($request, Throwable $exception)
    {
        // 驗證錯誤，回傳自定義錯誤格式
        if ($exception instanceof ValidationException) {
            return response()->json([
                'message' => '欄位驗證失敗',
                'errors' => $exception->errors(),
            ], 422);
        }

        // 認證錯誤，回傳未授權錯誤訊息
        if ($exception instanceof AuthenticationException) {
            return response()->json([
                'message' => '未授權，請登入後再操作。',
            ], 401);
        }

        // 資源未找到錯誤（404）
        if ($exception instanceof NotFoundHttpException) {
            return response()->json([
                'message' => '未找到資源。',
            ], 404);
        }

        // 一般異常（500）處理
        if ($exception instanceof Throwable) {
            return response()->json([
                'message' => '伺服器錯誤，請稍後再試。',
            ], 500);
        }

        // 如果異常類型不在上面處理範圍內，則交由父類別處理
        return parent::render($request, $exception);
    }
}
