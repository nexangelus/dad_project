<?php

namespace App\Listeners;

use App\Http\Resources\OrderForCustomerResource;
use App\Http\Resources\OrderForDelivererResource;
use App\Http\Resources\OrderForManagerResource;
use App\Http\Resources\OrderListForDelivererResource;
use App\Models\Order;
use App\Utils\SocketIO;

class OrderSaveListener {

    /* @var Order $order */
    /* @var Order $newOrder */
    public function __construct(Order $order) {
        $newOrder = Order::find($order->id);


        // Notify Customer that the order has been updated
        SocketIO::notifyUpdatedOrder(new OrderForCustomerResource($newOrder));
        SocketIO::notifyUpdateOrdersTableManager(new OrderForManagerResource($newOrder));


        // o pedido ficou para algum cozinheiro
        if($order->prepared_by != null && $order->getOriginal('prepared_by') == null) {
            SocketIO::notifyNewOrder($order->prepared_by);
        }

        if($order->status === "R") {
            SocketIO::notifyDeliverymansNewOrder(new OrderListForDelivererResource($newOrder));
        }

        // algum deliveryman ficou com o pedido, logo remover este da lista dos outros deliverymans
        if($order->delivered_by != null && $order->getOriginal('delivered_by') == null) {
            SocketIO::notifyOrderInTransitToOtherDelivery($order->id);
        }
    }

}
