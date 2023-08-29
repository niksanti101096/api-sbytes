<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\ProductsController;
use App\Http\Controllers\SalesController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post("register", [AuthController::class, "register"]);
Route::post("login", [AuthController::class, "login"]);

Route::resource('products', ProductsController::class);
Route::resource('sales', SalesController::class);

Route::get("fetchUsers", [AuthController::class, "index"]);

Route::group(["middleware" => ["auth:api"]], function () {
    Route::post("logout", [AuthController::class, "logout"]);
});