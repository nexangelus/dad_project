<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property integer $id
 * @property string $name
 * @property string $type
 * @property string $description
 * @property string $photo_url
 * @property float $price
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 * @property OrderItem[] $orderItems
 */
class Product extends Model {
    use HasFactory, SoftDeletes;

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
        'name',
        'type',
        'description',
        'photo_url',
        'price',
    ];

    /**
     * @return HasMany
     */
    public function orderItems()
    {
        return $this->hasMany('App\Models\OrderItem');
    }
}
