<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ManagerController;
use Illuminate\Http\Request;
use Illuminate\Routing\Middleware\ThrottleRequests;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\CookController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\CustomerController;
use App\Http\Controllers\Api\DelivererController;
use App\Http\Controllers\Api\StatisticsController;

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

Route::post('socket-logout', [AuthController::class, 'socketLogout'])->withoutMiddleware(['csrf']);

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
        Route::get('managers/products', [ProductController::class, 'getAllProductsWithDeleted']);

        Route::get('managers/dashboard', [ManagerController::class, 'getDashboardData']);
        Route::get('managers/dashboard/cook/{id}', [ManagerController::class, 'getOrderCookIsWorkingOn']);
        Route::get('managers/dashboard/delivery/{id}', [ManagerController::class, 'getOrderDeliverymanIsWorkingOn']);

        Route::patch('managers/order/{id}/cancel', [ManagerController::class, 'cancelOrder']);

        Route::patch('users/{id}/status/{status}', [ManagerController::class, 'blockUser']); // TODO Integrar este pedido na interface

        Route::get('users', [UserController::class, 'getAll'])->withoutMiddleware(ThrottleRequests::class);
        Route::get('users/{id}', [UserController::class, 'getUser'])->where('id', '[0-9]+');
        Route::post('users', [UserController::class, 'newEmployee']);
        Route::post('users/{id}/photo', [UserController::class, 'saveNewPhotoForUser']);
        Route::put('users/{id}', [UserController::class, 'updateUser']);
        Route::delete('users/{id}', [UserController::class, 'deleteUser']);

        //region Statistics


        //region Products

        Route::get('managers/statistics/currentMonth/products', [StatisticsController::class , 'getCurrentMonthAllProductsSales']);
        Route::get('managers/statistics/currentMonth/products/{id}', [StatisticsController::class , 'getCurrentMonthProductSales']);

        Route::get('managers/statistics/lastMonth/products', [StatisticsController::class , 'getLastMonthAllProductsSales']);
        Route::get('managers/statistics/lastMonth/products/{id}', [StatisticsController::class , 'getLastMonthProductSales']);

        Route::get('managers/statistics/last6Months/products', [StatisticsController::class , 'getLast6MonthsAllProductsSales']);
        Route::get('managers/statistics/last6Months/products/{id}', [StatisticsController::class , 'getLast6MonthsProductSales']);

        Route::get('managers/statistics/last2Years/products', [StatisticsController::class , 'getLast2YearsMonthsAllProductsSales']);
        Route::get('managers/statistics/last2Years/products/{id}', [StatisticsController::class , 'getLast2YearsMonthsProductSales']);



        Route::get('managers/statistics/lastMonth/deliverers', [StatisticsController::class , 'getLastMonthDeliverers']);

        //endregion


        Route::get('managers/statistics/daily/products', [StatisticsController::class , 'getDailyAllProductSales']);



        //endregion
    });
    //endregion

});

//Product
Route::get('products', [ProductController::class, 'getAllProducts']);

