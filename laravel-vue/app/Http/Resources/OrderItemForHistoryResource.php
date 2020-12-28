<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderItemForHistoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        /* @var \App\Models\OrderItem $this */
        return [
            'name' => $this->product->name,
            'unit_price' => $this->unit_price,
            'quantity' => $this->quantity,
            'sub_total_price' => $this->sub_total_price
        ];
    }
}
