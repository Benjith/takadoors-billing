<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ReportController;

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

// Route::get('/', function () {
//     return view('welcome');
// });
Route::get('/', [ReportController::class,'index']);
Route::any('/search', [ReportController::class,'search'])->name('search');
Route::any('/print/{from_date}/{to_date}/{fromserial}/{toserial}', [ReportController::class,'print'])->name('print');

//closing stock
Route::get('/closing_stock', [ReportController::class,'closing_stock_report']);
Route::get('/agent_wise', [ReportController::class,'agent_wise_report']);


