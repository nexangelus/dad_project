<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property integer $id
 * @property string $address
 * @property string $phone
 * @property string $nif
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 * @property User $user
 * @property Order[] $orders
 */
class Customer extends Model {
    use HasFactory, SoftDeletes;

    /**
     * The "type" of the auto-incrementing ID.
     *
     * @var string
     */
    protected $keyType = 'integer';

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * @var array
     */
    protected $fillable = ['address', 'phone', 'nif'];

    /**
     * @return BelongsTo
     */
    public function user() {
        return $this->belongsTo('App\Models\User', 'id');
    }

    /**
     * @return HasMany
     */
    public function orders() {
        return $this->hasMany('App\Models\Order');
    }
}
