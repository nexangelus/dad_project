<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderListForDelivererResource extends JsonResource
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
            'time_ready' => $this->current_status_at
        ];
    }
}
