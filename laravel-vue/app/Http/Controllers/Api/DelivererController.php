<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\OrderForDelivererResource;
use App\Http\Resources\OrderListForDelivererResource;
use App\Http\Resources\OrderResource;
use App\Models\Order;
use Illuminate\Http\Request;

class DelivererController extends Controller {
    public function getWorkToDo(Request $request) {
        $user = $request->user();
        $amDelivering = Order::query()->where(['prepared_by' => $user->id, 'status' => 'T'])->first();
        if ($amDelivering == null){
            return null;
        }
        return new OrderForDelivererResource($amDelivering);
    }

    public function getReadyOrders() {
        $ordersReady = Order::query()->where(['status' => 'R'])->orderBy('current_status_at', 'ASC')->get();
        if ($ordersReady== null){
            return null;
        }
        return OrderListForDelivererResource::collection($ordersReady);
    }
}
