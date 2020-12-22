<?php

// @formatter:off

namespace App\Models{
/**
 * App\Models\Cook
 *
 *
 */
	class Cook extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Customer
 *
 * @property int $id
 * @property string $address
 * @property string $phone
 * @property string|null $nif
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $deleted_at
 * @property-read \App\Models\User $user
 */
	class Customer extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Order
 *
 * @property int $id
 * @property string $status
 * @property int $customer_id
 * @property string|null $notes
 * @property string $total_price
 * @property string $date
 * @property int|null $prepared_by
 * @property int|null $delivered_by
 * @property string $opened_at
 * @property string $current_status_at
 * @property string|null $closed_at
 * @property int|null $preparation_time
 * @property int|null $delivery_time
 * @property int|null $total_time
 */
	class Order extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\User
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string $password
 * @property string|null $remember_token
 * @property string $type
 * @property int $blocked
 * @property string|null $photo_url
 * @property string|null $logged_at
 * @property string|null $available_at
 * @property string|null $deleted_at
 * @property-read \App\Models\Customer|null $customer
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read int|null $notifications_count
 */
	class User extends \Eloquent {}
}

