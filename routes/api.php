<?php

use App\Http\Controllers\Api\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\DepartmentController;
use App\Http\Controllers\Api\UserController;

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

//User
Route::post('login',                [AuthController::class, 'login']);
Route::post('register', [UserController::class, 'register']);
Route::middleware('auth:sanctum')->post('logout', [AuthController::class, 'logout']);
Route::middleware('auth:sanctum')->get('users/me', [UserController::class, 'me']);



Route::get('test', [UserController::class, 'test']);
Route::middleware('auth:sanctum')->get('users/me', [UserController::class, 'me']);
//Route::middleware('auth:sanctum')->put('users/photo', [UserController::class, 'uploadPhoto']);
Route::post('user/photo', [UserController::class, 'uploadPhoto']);

