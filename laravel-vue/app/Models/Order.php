<?php

namespace App\Models;

use App\Listeners\OrderSaveListener;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property integer $id
 * @property integer $customer_id
 * @property integer $prepared_by
 * @property integer $delivered_by
 * @property string $status
 * @property string $notes
 * @property float $total_price
 * @property string $date
 * @property string $opened_at
 * @property string $current_status_at
 * @property string $closed_at
 * @property int $preparation_time
 * @property int $delivery_time
 * @property int $total_time
 * @property string $created_at
 * @property string $updated_at
 * @property Customer $customer
 * @property User $cook
 * @property User $delivery
 * @property OrderItem[] $orderItems
 */
class Order extends Model {
    use HasFactory;

    public function __construct($object = null) {
        if($object != null) {
            foreach($object as $property => $value) {
                $this->$property = $value;
            }
        }
    }

    protected $dispatchesEvents = ['saved' => OrderSaveListener::class];

    /**
     * The "type" of the auto-incrementing ID.
     *
     * @var string
     */
    protected $keyType = 'integer';

    /**
     * @var array
     */
    protected $fillable = [
        'customer_id',
        'prepared_by',
        'delivered_by',
        'status',
        'notes',
        'total_price',
        'date',
        'opened_at',
        'current_status_at',
        'closed_at',
        'preparation_time',
        'delivery_time',
        'total_time'
    ];

    /**
     * @return BelongsTo
     */
    public function customer() {
        return $this->belongsTo('App\Models\Customer');
    }

    /**
     * @return BelongsTo
     */
    public function cook() {
        return $this->belongsTo('App\Models\User', 'prepared_by');
    }

    /**
     * @return BelongsTo
     */
    public function delivery() {
        return $this->belongsTo('App\Models\User', 'delivered_by');
    }

    /**
     * @return HasMany
     */
    public function orderItems() {
        return $this->hasMany('App\Models\OrderItem');
    }
}
