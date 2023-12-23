<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\RawMaterialController;
use App\Http\Controllers\StockController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\UserController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::post('/user/create', 'AuthController@register');
Route::post('/login', 'AuthController@login');
Route::post('/changepassword', 'AuthController@ChangePassword');

Route::middleware('auth:sanctum')->group(function () {
    //Manage material
    Route::get('/materials', [RawMaterialController::class,'index']);
    Route::post('/materials/{id}', [RawMaterialController::class,'update']);
    Route::post('/materials',[RawMaterialController::class,'store']);
    Route::delete('/materials/{id}',[RawMaterialController::class,'destroy']);


    //Manage Stock
    Route::get('/stock', [StockController::class,'index']);
    Route::post('/stock/{id}', [StockController::class,'update']);
    Route::post('/stock',[StockController::class,'store']);
    Route::delete('/stock/{id}',[StockController::class,'destroy']);
    Route::get('/stock/search', [StockController::class,'search']);

    //Manage Order

    // Route::get('/me', 'AuthController@me');
    Route::get('/order', [OrderController::class,'index']);
    Route::post('/order/{id}', [OrderController::class,'update']);
    Route::post('/order',[OrderController::class,'store']);
    Route::delete('/order/{id}',[OrderController::class,'destroy']); 
    Route::post('image-upload', [OrderController::class, 'uploadImage']);

    //Manage User
    Route::get('/user/list', [UserController::class,'index']);
    Route::post('/user/{id}', [UserController::class,'update']);
    Route::delete('/user/{id}',[UserController::class,'destroy']);

});
Route::get('/export',[OrderController::class, 'get_order_data'])->name('order.export');

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });
