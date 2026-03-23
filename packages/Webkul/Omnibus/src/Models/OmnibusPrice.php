<?php

namespace Webkul\Omnibus\Models;

use Illuminate\Database\Eloquent\Model;
use Webkul\Omnibus\Contracts\OmnibusPrice as OmnibusPriceContract;
use Webkul\Product\Models\ProductProxy;

class OmnibusPrice extends Model implements OmnibusPriceContract
{
    protected $table = 'product_omnibus_prices';

    protected $fillable = [
        'product_id',
        'price',
        'special_price',
    ];

    public function product()
    {
        return $this->belongsTo(ProductProxy::modelClass());
    }
}
