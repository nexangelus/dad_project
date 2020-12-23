<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * @property integer $id
 * @property string $name
 * @property string $email
 * @property string $email_verified_at
 * @property string $password
 * @property string $remember_token
 * @property string $created_at
 * @property string $updated_at
 * @property string $type
 * @property boolean $blocked
 * @property string $photo_url
 * @property string $logged_at
 * @property string $available_at
 * @property string $deleted_at
 * @property Customer $customer
 * @property Order[] $ordersDelivered
 * @property Order[] $ordersPrepared
 */
class User extends Authenticatable {
    use HasFactory, Notifiable;

    public function __construct($object = null) {
        if($object != null) {
            foreach($object as $property => $value) {
                $this->$property = $value;
            }
        }
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'email', 'password', 'email_verified_at', 'photo_url',];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = ['password', 'remember_token',];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = ['email_verified_at' => 'datetime',];

    /**
     * @return HasOne
     */
    public function customer() {
        return $this->hasOne('App\Models\Customer', 'id');
    }

    /**
     * @return HasMany
     */
    public function ordersDelivered() {
        return $this->hasMany('App\Models\Order', 'delivered_by');
    }

    /**
     * @return HasMany
     */
    public function ordersPrepared() {
        return $this->hasMany('App\Models\Order', 'prepared_by');
    }
}
