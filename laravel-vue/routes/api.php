<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ManagerController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\CookController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\CustomerController;
use App\Http\Controllers\Api\DelivererController;

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
    Route::post('orders', [OrderController::class, 'postOrder']);
    //endregion

    //region Customer
    Route::get('customers/order', [CustomerController::class, 'getOrders']);
    Route::get('customers/history', [CustomerController::class, 'getOrderHistory']);
    //endregion

    //region Cook
    Route::middleware("checkUserCook")->group(function() {
        Route::get('cooks/work', [CookController::class, 'getWorkToDo']);
        Route::patch('cooks/ready', [CookController::class, 'setOrderReady']);
    });
    //endregion

    //region Deliverer
    Route::middleware("checkUserDeliverer")->group(function() {
        Route::get('deliverers/work', [DelivererController::class, 'getWorkToDo']);
        Route::get('deliverers/orders', [DelivererController::class, 'getReadyOrders']);
        Route::patch('deliverers/orders/{id}/transit', [DelivererController::class, 'setOrderTransit']);
        Route::patch('deliverers/orders/{id}/delivered', [DelivererController::class, 'setOrderDelivered']);
    });
    //endregion

    //region Manager
    Route::middleware("checkUserManager")->group(function() {
        Route::get('managers/dashboard', [ManagerController::class, 'getDashboardData']);
        Route::get('managers/dashboard/cook/{id}', [ManagerController::class, 'getOrderCookIsWorkingOn']);
        Route::get('managers/dashboard/delivery/{id}', [ManagerController::class, 'getOrderDeliverymanIsWorkingOn']);

        Route::patch('users/{id}/status/{status}', [ManagerController::class, 'blockUser']); // TODO Integrar este pedido na interface
    });
    //endregion

});

//Product
Route::get('products', [ProductController::class, 'getAllProducts']);

