<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ManagerController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\DepartmentController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\CookController;
use App\Http\Controllers\Api\ProductController;

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

//region User
Route::post('login', [AuthController::class, 'login']);
Route::post('register', [UserController::class, 'register']);
Route::middleware('auth:sanctum')->post('logout', [AuthController::class, 'logout']);

Route::middleware('auth:sanctum')->get('users/me', [UserController::class, 'me']);
Route::middleware('auth:sanctum')->post('users/photo', [UserController::class, 'uploadPhoto']);
Route::middleware('auth:sanctum')->put('users/password', [UserController::class, 'changePassword']);
Route::middleware('auth:sanctum')->put('users', [UserController::class, 'update']);
Route::middleware('auth:sanctum')->post('users/socketID', [UserController::class, 'notifyNewSocketID']);
//endregion
//region Cook
Route::middleware('auth:sanctum')->get('cooks/work', [CookController::class, 'getWorkToDo']);
//endregion


//region Manager
Route::middleware('auth:sanctum')->get('managers/dashboard', [ManagerController::class, 'getDashboardData']);
Route::middleware('auth:sanctum')->get('managers/dashboard/cooksWorking', [ManagerController::class, 'getCooksWorking']);
Route::middleware('auth:sanctum')->get('managers/dashboard/cook/{id}', [ManagerController::class, 'getOrderCookIsWorkingOn']);
//endregion

// middleware, policies, authorization, auth:sanctum obrigatório


//Product
Route::get('products', [ProductController::class, 'getAllProducts']);
Route::get('test', [UserController::class, 'test']);

