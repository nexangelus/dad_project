<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use App\Utils\SocketIO;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Date;

class OrderController extends Controller
{
    public function postOrder(Request $request){
        $items = $request->cart;
        $note = $request->note;
        $total_price = 0;
        foreach ($items as $item){
            $product = Product::query()->where('id', $item['id'])->first();
            $total_price += $item['quantity']*$product->price;
        }
        $order = new Order();
        $order->customer_id = $request->user()->id;
        $order->notes = $note;
        $order->total_price = $total_price;
        $order->date = date('Y-m-d');
        $order->opened_at = new \DateTime();
        $order->current_status_at = new \DateTime();

        // verificar se existem pedidos à espera (significa que este irá logo para a fila de espera)
        if(Order::query()->where(['status' => 'H'])->count() > 0) {
            $order->status = 'H';
        } else {
            /* @var User[] $loggedInCooks */
            // buscar os cooks que têm login feito
            $loggedInCooks = User::query()->where(['type' => "EC"])->whereNotNull('logged_at')->orderBy('available_at')->get();
            if(count($loggedInCooks) > 0) { // se houver algum verificar se ele está com algum pedido
                foreach ($loggedInCooks as $cook) {
                    if(!Order::query()->where(['prepared_by' => $cook->id, 'status' => 'P'])->exists()) { // se não estiver com nenhum pedido
                        $order->prepared_by = $cook->id; // definir que este pedido está a ser preparado por este cook
                        $order->status = 'P';
                        break;
                    }
                }
                if(!$order->prepared_by)
                    $order->status = 'H'; // não houve ninguém disponível para ficar com este pedido
            } else {
                $order->status = 'H'; // não havia ninguém online para este pedido
            }
        }

        $order->save();
        foreach ($items as $item){
            $product = Product::query()->where('id', $item['id'])->first();
            $sub_total = $item['quantity']*$product->price;
            $order_items = new OrderItem();
            $order_items->order_id = $order->id;
            $order_items->product_id = $product->id;
            $order_items->quantity = $item['quantity'];
            $order_items->unit_price = $product->price;
            $order_items->sub_total_price = $sub_total;
            $order_items->save();
        }

        if($order->prepared_by) {
            SocketIO::notifyNewOrder($order->prepared_by);
        }

        return $order;
    }
}
