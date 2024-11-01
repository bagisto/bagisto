<?php

namespace Frosko\FairySync\Models\Sync;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property int $order_id
 * @property string $first_name
 * @property string $last_name
 * @property string $email
 * @property string $telephone
 * @property string $city
 * @property string $postal_code
 * @property string $country_code
 * @property Carbon $order_date
 * @property int $pay_type
 * @property int $pay_method
 * @property float $sub_total
 * @property float $total
 * @property float $shipping_amount
 * @property float $discounts
 * @property string $currency
 * @property string $currency_rate
 * @property int $synced
 * @property ?array $errors
 * @property ?string $comment
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class Order extends Model
{
    protected $connection = 'sync';

    protected $fillable = [
        'order_id',
        'first_name',
        'last_name',
        'email',
        'telephone',
        'city',
        'postal_code',
        'country_code',
        'pay_type',
        'pay_method',
        'sub_total',
        'total',
        'shipping_amount',
        'discounts',
        'currency',
        'currency_rate',
        'synced',
        'errors',
        'comment',
        'order_date',
    ];

    protected $casts = [
        'errors'     => 'json',
        'order_date' => 'date',
    ];

    public function orderItems(): HasMany
    {
        return $this->hasMany(
            OrderItem::class,
            'order_id',
            'order_id',
        );
    }

    public function address(): BelongsTo
    {
        return $this->belongsTo(
            Address::class,
            'order_id',
            'order_id',
        );
    }
}
