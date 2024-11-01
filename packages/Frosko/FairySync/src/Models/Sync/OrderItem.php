<?php

namespace Frosko\FairySync\Models\Sync;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $order_id
 * @property string $sku
 * @property int $quantity
 * @property float $price
 * @property float $total
 * @property float $tax
 */
class OrderItem extends Model
{
    protected $connection = 'sync';

    protected $fillable = [
        'order_id',
        'sku',
        'quantity',
        'price',
        'total',
        'tax',
    ];

    public $timestamps = false;

    public function order(): BelongsTo
    {
        return $this->belongsTo(
            Order::class,
            'order_id',
            'order_id',
        );
    }
}
