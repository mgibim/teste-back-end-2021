<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiController;
use App\Http\Controllers\ProductController;

Route::post('auth/login', [ApiController::class, 'login']);
Route::post('auth/refresh', [ApiController::class, 'refresh']);

Route::group(['middleware' => ['jwt.verify']], function() {
    Route::get('auth/logout', [ApiController::class, 'logout']);
    Route::get('auth/me', [ApiController::class, 'me']);
    
    Route::get('product/index', [ProductController::class, 'index']);
    Route::get('product/show/{id}', [ProductController::class, 'show']);
    Route::post('product/create', [ProductController::class, 'store']);
    Route::put('product/update/{id}',  [ProductController::class, 'update']);
    Route::delete('product/delete/{id}',  [ProductController::class, 'destroy']);
});