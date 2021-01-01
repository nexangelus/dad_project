<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductClientResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $data = [
            'id' => $this->id,
            'name' => $this->name,
            'type' => $this->type,
            'typeFormatted' => ucwords($this->type),
            'description' => $this->description,
            'photo_url' =>  asset('storage/products/'.$this->photo_url),
            'price' => $this->price,
            'quantity' => 1
        ];
        return $data;
    }
}
