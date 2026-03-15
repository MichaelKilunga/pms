<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::prefix('sync')->middleware('auth:sanctum')->group(function () {
    Route::get('/pull', [\App\Http\Controllers\Api\SyncController::class, 'pull']);
    Route::post('/push', [\App\Http\Controllers\Api\SyncController::class, 'push']);
});
