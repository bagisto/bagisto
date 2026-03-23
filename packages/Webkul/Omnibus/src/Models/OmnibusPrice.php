<?php

namespace Webkul\Omnibus\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Webkul\Omnibus\Contracts\OmnibusPrice as OmnibusPriceContract;
use Webkul\Product\Models\ProductProxy;

/**
 * @property int $id
 * @property int $product_id
 * @property int $channel_id
 * @property string $currency_code
 * @property float $price
 * @property string $date
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class OmnibusPrice extends Model implements OmnibusPriceContract
{
    protected $table = 'product_omnibus_prices';

    protected $fillable = [
        'product_id',
        'channel_id',
        'currency_code',
        'price',
        'recorded_at',
    ];

    public function product()
    {
        return $this->belongsTo(ProductProxy::modelClass());
    }
}
