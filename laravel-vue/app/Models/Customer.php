<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model {
    use HasFactory;

    protected $fillable = ['address', 'phone', 'nif'];

    public function user() {
        return $this->belongsTo(User::class, "id");
    }
}
