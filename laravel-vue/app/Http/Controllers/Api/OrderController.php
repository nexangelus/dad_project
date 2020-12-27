<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
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
        $order->status = 'H';
        $order->customer_id = $request->user()->id;
        $order->notes = $note;
        $order->total_price = $total_price;
        $order->date = date('Y-m-d');
        $order->opened_at = new \DateTime();
        $order->current_status_at = new \DateTime();
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
        return $order;
    }
}
