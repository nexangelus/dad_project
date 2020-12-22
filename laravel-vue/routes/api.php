<?php

use App\Http\Controllers\Api\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\DepartmentController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\CookController;

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
//endregion
//region Cook
Route::middleware('auth:sanctum')->get('cook/work', [CookController::class, 'getWorkToDo']);
//endregion

Route::get('test', [UserController::class, 'test']);

