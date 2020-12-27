<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property integer $id
 * @property integer $order_id
 * @property integer $product_id
 * @property int $quantity
 * @property float $unit_price
 * @property float $sub_total_price
 * @property Order $order
 * @property Product $product
 */
class OrderItem extends Model {
    use HasFactory;

    public $timestamps = false;

    public function __construct($object = null) {
        if($object != null) {
            foreach($object as $property => $value) {
                $this->$property = $value;
            }
        }
    }

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
        'order_id',
        'product_id',
        'quantity',
        'unit_price',
        'sub_total_price'
    ];

    /**
     * @return BelongsTo
     */
    public function order() {
        return $this->belongsTo('App\Models\Order');
    }

    /**
     * @return BelongsTo
     */
    public function product() {
        return $this->belongsTo('App\Models\Product');
    }
}
