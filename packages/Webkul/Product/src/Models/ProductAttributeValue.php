<?php

namespace Webkul\Product\Models;

use Illuminate\Database\Eloquent\Model;
use Webkul\Attribute\Models\AttributeProxy;
use Webkul\Channel\Models\ChannelProxy;
use Webkul\Product\Contracts\ProductAttributeValue as ProductAttributeValueContract;

class ProductAttributeValue extends Model implements ProductAttributeValueContract
{
    public $timestamps = false;

    /**
     * @var array
     */
    public static $attributeTypeFields = [
        'text'        => 'text_value',
        'textarea'    => 'text_value',
        'price'       => 'float_value',
        'boolean'     => 'boolean_value',
        'select'      => 'integer_value',
        'multiselect' => 'text_value',
        'datetime'    => 'datetime_value',
        'date'        => 'date_value',
        'file'        => 'text_value',
        'image'       => 'text_value',
        'checkbox'    => 'text_value',
    ];

    protected $fillable = [
        'product_id',
        'attribute_id',
        'channel_id',
        'locale',
        'channel',
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
        return $this->belongsTo(AttributeProxy::modelClass());
    }

    /**
     * Get the product that owns the attribute value.
     */
    public function product()
    {
        return $this->belongsTo(ProductProxy::modelClass());
    }

    /**
     * Get the channel that owns the attribute value.
     */
    public function channel()
    {
        return $this->belongsTo(ChannelProxy::modelClass());
    }
}