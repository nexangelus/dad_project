<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserOrderForManagerResource extends JsonResource {
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request) {
        /* @var \App\Models\Order $this */
        return [
            'id' => $this->id,
            'customer' => $this->customer->user->name,
            'opened_at' => $this->opened_at,
            'cook' => ($this->cook) ? $this->cook->name : '',
            'deliveryman' => ($this->delivery) ? $this->delivery->name : '',
            'current_status_at' => $this->current_status_at,
            'status' => $this->status,
            'notes' => $this->notes,
            'total_price' => $this->total_price,
            'closed_at' => $this->closed_at,
            'preparation_time' => $this->preparation_time,
            'delivery_time' => $this->delivery_time,
            'total_time' => $this->total_time,
            'order_items' => OrderItemForHistoryResource::collection($this->orderItems)
        ];
    }
}
