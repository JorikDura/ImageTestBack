<?php

use App\Http\Controllers\Api\V1\ImageController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'v1'], function () {
    Route::get('image', [ImageController::class, 'index']);
    Route::post('image', [ImageController::class, 'store']);
    Route::get('image/{image}', [ImageController::class, 'show']);
    Route::delete('image/{image}', [ImageController::class, 'destroy']);
});
