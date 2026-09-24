<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\AuthController;


Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::apiResource(
    'categories',
    CategoryController::class
);

Route::apiResource(
    'products',
    ProductController::class
);


Route::post('/auth/register', [AuthController::class, 'register']);
Route::post('/auth/login', [AuthController::class, 'login']);
Route::get('/auth/me',[AuthController::class,'me'])->middleware('auth:api');
Route::post('/auth/logout', [AuthController::class, 'logout'])->middleware('auth:api');
Route::get('/admin/test',function(){
    return response()->json([
        'message' => 'Welcome Admin'
    ]);
})->middleware(['auth:api','role:ADMIN']);