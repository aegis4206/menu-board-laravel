<?php

use App\Http\Controllers\API\PostController;
use App\Http\Controllers\API\TypeController;
use App\Http\Controllers\API\TabController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return 'welcome';
});
// Route::apiResource('posts', PostController::class);
Route::middleware('api')->group(function () {
    Route::apiResource('posts', PostController::class);
    Route::apiResource('types', TypeController::class);
    Route::apiResource('tabs', TabController::class);


    // storage/uploads/ 走web模式會被擋cors 改用porxy
    Route::get('/image-proxy/uploads/{filename}', function ($filename) {
        $path = storage_path("app/public/uploads/{$filename}");

        if (!file_exists($path)) {
            abort(404);
        }

        $mime = mime_content_type($path);
        $contents = file_get_contents($path);

        return response($contents, 200)
            ->header('Content-Type', $mime)
            ->header('Access-Control-Allow-Origin', '*');
    });
});
