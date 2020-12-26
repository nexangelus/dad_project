<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderForManagerResource extends JsonResource {
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
            'customer' => new UserResource($this->customer->user),
            'opened_at' => $this->opened_at,
            'cook' => $this->cook,
            'deliveryman' => $this->delivery,
            'current_status_at' => $this->current_status_at,
            'status' => $this->status,
            'notes' => $this->notes,
            'total_price' => $this->total_price,
            'closed_at' => $this->closed_at,
            'preparation_time' => $this->preparation_time,
            'delivery_time' => $this->delivery_time,
            'total_time' => $this->total_time,
        ];
    }
}
