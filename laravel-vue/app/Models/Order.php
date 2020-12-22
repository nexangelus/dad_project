<?php

namespace App\Models;

use Carbon\Traits\Date;
use DateTime;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'status',
        'customer_id',
        'notes',
        'total_price',
        'date',
        'prepared_by',
        'delivered_by',
        'opened_at',
        'current_status_at',
        'closed_at',
        'preparation_time',
        'delivery_time',
        'total_time'];
}
