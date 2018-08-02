<?php

namespace Webkul\Product\Models;

use Illuminate\Database\Eloquent\Model;
use Webkul\Attribute\Models\Attribute;
use Webkul\Product\Models\Product;
use Webkul\Channel\Models\Channel;

class ProductAttributeValue extends Model
{
    public $timestamps = false;

    protected $with = ['attribute'];

    protected $fillable = [
            'product_id',
            'attribute_id',
            'channel_id',
            'locale',
            'text_value',
            'boolean_value',
            'integer_value',
            'float_value',
            'datetime_value',
            'date_value',
            'json_value'
        ];

    /**
     * Get the attribute that owns the attribute value.
     */
    public function attribute()
    {
        return $this->belongsTo(Attribute::class);
    }

    /**
     * Get the product that owns the attribute value.
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Get the channel that owns the attribute value.
     */
    public function channel()
    {
        return $this->belongsTo(Channel::class);
    }
}