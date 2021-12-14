<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\MerchantController;
use App\Http\Controllers\OutletController;
use App\Http\Controllers\TransactionController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::group([
    "prefix" => "v1"
], function($router){
    Route::post('login', [AuthController::class, "login"]);
    Route::post('register', [AuthController::class, "register"]);
});

Route::group([
    "middleware"=>"auth.jwt",
    "prefix" => "v1"
], function($router){
    Route::get("report/merchant/{id}", [TransactionController::class, "report_merchant"]);
    Route::post("create/merchant", [MerchantController::class, "create"]);
    Route::post("create/outlet", [OutletController::class, "create"]);
    Route::get("report/merchant/{mid}/outlet/{oid}", [TransactionController::class, "report_outlet"]);
    Route::post("create/transactions", [TransactionController::class, "store"]);
});
