<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\Auth\LoginController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::get('/', [LoginController::class,'index']);

Route::middleware(['web','auth'])->group(function () {
// Route::group(['middleware' => 'web'], function () {
    Route::get('/home', [App\Http\Controllers\ReportController::class, 'index'])->name('home');

    Route::any('/search', [ReportController::class,'search'])->name('search');
    Route::any('/print/{from_date}/{to_date}/{fromserial}/{toserial}/{agent}/{code}', [ReportController::class,'print'])->name('print');
    // Route::any('/print', [ReportController::class,'print'])->name('print');
    //closing stock
    Route::get('/closing_stock', [ReportController::class,'closing_stock_report']);
    Route::get('/agent_wise', [ReportController::class,'agent_wise_report']);
    Route::get('/gate_pass', [ReportController::class,'gate_pass_report']);
    Route::any('/agent_search', [ReportController::class,'agent_report_search'])->name('agent_search');

    Route::get('/orders', [App\Http\Controllers\Admin\OrderController::class, 'index'])->name('orders');

    Route::post('/orders', [App\Http\Controllers\Admin\OrderController::class, 'create'])->name('order.bulk.create');

    Route::get('/production', [App\Http\Controllers\Admin\OrderController::class, 'getProductionOrders'])->name('productionorders');
    Route::post('/production', [App\Http\Controllers\Admin\OrderController::class, 'update'])->name('updateOrders');
    Route::any('/production-print', [App\Http\Controllers\Admin\OrderController::class,'productionPrint'])->name('print');

    Route::get('/dispatch', [App\Http\Controllers\Admin\OrderController::class, 'getDispatchOrders'])->name('dispatchorders');
    Route::any('/dispatch_search', [App\Http\Controllers\Admin\OrderController::class, 'dispatchSearch'])->name('dispatch_search');
});


Auth::routes();



