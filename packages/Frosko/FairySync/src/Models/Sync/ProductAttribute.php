<?php

namespace Frosko\FairySync\Models\Sync;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $product_id
 * @property string $key
 * @property string $value
 * @property int $type
 */
class ProductAttribute extends Model
{
    protected $connection = 'sync';

    protected $fillable = [
        'product_id',
        'key',
        'value',
        'type',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(
            Product::class,
            'id',
            'product_id',
        );
    }
}
