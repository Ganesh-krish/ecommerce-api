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
)
    ->middleware('auth:api')
    ->middlewareFor(['index', 'show'], 'permission:categories.view')
    ->middlewareFor('store', 'permission:categories.create')
    ->middlewareFor('update', 'permission:categories.update')
    ->middlewareFor('destroy', 'permission:categories.delete');


Route::apiResource(
    'products',
    ProductController::class
)
    ->middleware('auth:api')
    ->middlewareFor(['index', 'show'], 'permission:products.view')
    ->middlewareFor('store', 'permission:products.create')
    ->middlewareFor('update', 'permission:products.update')
    ->middlewareFor('destroy', 'permission:products.delete');
    

Route::post('/auth/register', [AuthController::class, 'register']);
Route::post('/auth/login', [AuthController::class, 'login']);
Route::get('/auth/me',[AuthController::class,'me'])->middleware('auth:api');
Route::post('/auth/logout', [AuthController::class, 'logout'])->middleware('auth:api');
Route::get('/admin/test',function(){
    return response()->json([
        'message' => 'Welcome Admin'
    ]);
})->middleware(['auth:api','role:ADMIN']);


// Route::get('/products', [ProductController::class, 'index'])
//     ->middleware('auth:api');

// Route::get('/products/{product}', [ProductController::class, 'show'])
//     ->middleware('auth:api');

// Route::post('/products', [ProductController::class, 'store'])
//     ->middleware(['auth:api', 'role:ADMIN']);

// Route::put('/products/{product}', [ProductController::class, 'update'])
//     ->middleware(['auth:api', 'role:ADMIN']);

// Route::patch('/products/{product}', [ProductController::class, 'update'])
//     ->middleware(['auth:api', 'role:ADMIN']);

// Route::delete('/products/{product}', [ProductController::class, 'destroy'])
//     ->middleware(['auth:api', 'role:ADMIN']);