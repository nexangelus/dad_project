<?php

namespace App\Http\Resources;

use App\Models\Order;
use Illuminate\Http\Resources\Json\JsonResource;

class EmployeesForManagerResource extends JsonResource {
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request) {
        /* @var \App\Models\User $this */

        $status = null;
        if($this->logged_at != null) {
            if($this->type == "EC") {
                $order = Order::query()->where(['prepared_by' => $this->id])->orderBy('id', "desc")->limit(1)->first();
                if ($order != null && $order->status == 'P') {
                    $status = "busy";
                } else {
                    $status = "wait";
                }
            } else if($this->type == "ED") {
                $order = Order::query()->where(['delivered_by' => $this->id])->orderBy('id', "desc")->limit(1)->first();
                if ($order != null && $order->status == 'T') {
                    $status = "busy";
                } else {
                    $status = "wait";
                }
            }
        }

        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'photo_url' => asset('storage/fotos/'.$this->photo_url),
            'blocked' => $this->blocked,
            'type' => $this->type,
            'available_at' => $this->available_at,
            'created_at' => $this->created_at,
            'deleted_at' => $this->deleted_at,
            'updated_at' => $this->updated_at,
            'logged_at' => $this->logged_at,
            'status' => $status,
        ];
    }
}
