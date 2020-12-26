<?php

namespace App\Http\Resources;

use App\Models\Order;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        /* @var Order $this */
        $items = $this->orderItems;
        $list = [];
        foreach ($items as $value){
            $list[] = ["name" => $value->product->name, "quantity" => $value->quantity];
        }
        $data = [
            'id' => $this->id,
            'notes' => $this->notes,
            'current_status_at' => $this->current_status_at,
            'customerName' => $this->customer->user->name,
            'order_items' => $list,
        ];
        return $data;
    }
}
