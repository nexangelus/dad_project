<?php

namespace App\Http\Resources;

use App\Models\OrderItem;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderForDelivererResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        /* @var \App\Models\Order $this */
        return [
            'id' => $this->id,
            'name' => $this->customer->user->name,
            'address' => $this->customer->address,
            'phone' => $this->customer->phone,
            'email' => $this->customer->user->email,
            'photo' => $this->current_status_at,
            'time_delivery' => $this->current_status_at,
            'notes' => $this->notes,
            'order_items' => OrderItemResource::collection($this->orderItems)
        ];
    }
}
