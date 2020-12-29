<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\OrderForCustomerResource;
use App\Http\Resources\OrderForDelivererResource;
use App\Http\Resources\OrderForManagerResource;
use App\Http\Resources\OrderListForDelivererResource;
use App\Http\Resources\OrderResource;
use App\Models\Order;
use App\Models\User;
use App\Utils\SocketIO;
use Illuminate\Http\Request;

class DelivererController extends Controller {
    public function getWorkToDo(Request $request) {
        $user = $request->user();
        $amDelivering = Order::query()->where(['delivered_by' => $user->id, 'status' => 'T'])->first();
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

    public function setOrderTransit(Request $request, $id){
        /* @var Order $order*/ /* @var User $user */
        $user = $request->user();
        $order = Order::query()->where('id',$id)->first();
        if ($order!= null){
            $order->status = 'T';
            $order->delivered_by = $user->id;
            $order->current_status_at = new \DateTime();
            $order->save();
            SocketIO::notifyUpdateOrdersTableManager(new OrderForManagerResource($order));
            SocketIO::notifyUpdatedOrder(new OrderForCustomerResource($order));
            return new OrderForDelivererResource($order);
        }
        return null;
    }

    public  function setOrderDelivered(Request  $request, $id){
        /* @var Order $order*/ /* @var User $user */
        $user = $request->user();
        $order = Order::query()->where(['id'=>$id,'delivered_by' => $user->id])->first();
        if ($order!= null){
            $order->status = 'D';
            $timeDeliverStarted = new \DateTime($order->current_status_at);
            $timeNow = new \DateTime();
            $timeSpent = $timeNow->getTimestamp() - $timeDeliverStarted->getTimestamp();
            $order->current_status_at = $timeNow;
            $order->closed_at = $timeNow;
            $order->delivery_time = $timeSpent;
            $order->save();
            SocketIO::notifyUpdateOrdersTableManager(new OrderForManagerResource($order));
            SocketIO::notifyUpdatedOrder(new OrderForCustomerResource($order));
        }
        return null;
    }
}
