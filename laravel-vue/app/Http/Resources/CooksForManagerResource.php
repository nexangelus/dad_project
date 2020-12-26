<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CooksForManagerResource extends JsonResource {
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request) {
        /* @var \App\Models\User $this */
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'photo_url' => asset('storage/fotos/'.$this->photo_url),
            'blocked' => $this->blocked,
            'available_at' => $this->available_at,
            'created_at' => $this->created_at,
            'deleted_at' => $this->deleted_at,
            'updated_at' => $this->updated_at,
            'logged_at' => $this->logged_at,
            'status' => $this->status,
        ];
    }
}
