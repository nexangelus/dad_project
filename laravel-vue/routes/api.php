<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ManagerController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\DepartmentController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\CookController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\OrderController;

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


Route::post('login', [AuthController::class, 'login']);
Route::post('register', [UserController::class, 'register']);

Route::middleware('auth:sanctum')->group(function() {

    //region User
    Route::post('logout', [AuthController::class, 'logout']);

    Route::get('users/me', [UserController::class, 'me']);
    Route::post('users/photo', [UserController::class, 'uploadPhoto']);
    Route::put('users/password', [UserController::class, 'changePassword']);
    Route::put('users', [UserController::class, 'update']);
    Route::post('users/socketID', [UserController::class, 'notifyNewSocketID']);
    //endregion

    //region Order
    Route::post('order', [OrderController::class, 'postOrder']);
    //endregion

    //region Cook
    Route::middleware("checkUserCook")->group(function() {
        Route::get('cooks/work', [CookController::class, 'getWorkToDo']);
        Route::patch('cooks/ready', [CookController::class, 'setOrderReady']);
    });

    //endregion


    //region Manager
    Route::middleware("checkUserManager")->group(function() {
        Route::get('managers/dashboard', [ManagerController::class, 'getDashboardData']);
        Route::get('managers/dashboard/cook/{id}', [ManagerController::class, 'getOrderCookIsWorkingOn']);
        Route::get('managers/dashboard/delivery/{id}', [ManagerController::class, 'getOrderDeliverymanIsWorkingOn']);
    });
    //endregion

});

//Product
Route::get('products', [ProductController::class, 'getAllProducts']);
Route::get('test', [UserController::class, 'test']);

