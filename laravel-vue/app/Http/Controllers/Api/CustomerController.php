<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\OrderForCustomerResource;
use App\Models\Order;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function getOrders(Request $request){
        $user = $request->user();
        $orders = Order::query()->where('customer_id', $user->id)->whereNull('closed_at')->whereNotIn('status', ['D','C'])->get();
        return OrderForCustomerResource::collection($orders);
    }
}
