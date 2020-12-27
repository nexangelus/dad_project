<?php

namespace App\Http\Resources;

use App\Models\OrderItem;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderForCustomerResource extends JsonResource
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
        $responsible = '';
        if ($this->prepared_by!=null){
            $responsible = $this->cook->name;
        }else if($this->delivered_by!=null){
            $responsible = $this->delivery->name;
        }

        return [
            'id' => $this->id,
            'customer_id' => $this->customer_id,
            'opened_at' => $this->opened_at,
            'current_status_at' => $this->current_status_at,
            'status' => $this->status,
            'notes' => $this->notes,
            'total_price' => $this->total_price,
            'order_items' => OrderItemResource::collection($this->orderItems),
            'responsible' => $responsible
        ];
    }
}
