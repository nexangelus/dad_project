<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $data = [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'email_verified_at' => $this->email_verified_at,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'deleted_at' => $this->deleted_at,
            'blocked' => $this->blocked,
            'type' => $this->type,
            'photo_url' => $this->photo_url ? asset('storage/fotos/'.$this->photo_url) : asset('img/default.png'),
        ];
        if($this->type === "C") {
            $data['address'] = $this->customer->address;
            $data['phone'] = $this->customer->phone;
            $data['nif'] = $this->customer->nif;
        }
        return $data;
    }
}
