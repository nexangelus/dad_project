<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Order extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $items = $this->orderItems;
        $list = [];
        foreach ($items as $value){
            $list[] = $value->product->name;
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
