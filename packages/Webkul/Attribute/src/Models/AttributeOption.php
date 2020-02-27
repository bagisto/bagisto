<?php

namespace Webkul\Attribute\Models;

use Illuminate\Support\Facades\Storage;
use Webkul\Core\Eloquent\TranslatableModel;
use Webkul\Attribute\Contracts\AttributeOption as AttributeOptionContract;

class AttributeOption extends TranslatableModel implements AttributeOptionContract
{
    public $timestamps = false;

    public $translatedAttributes = ['label'];

    protected $fillable = ['admin_name', 'swatch_value', 'sort_order', 'attribute_id'];

    /**
     * Get the attribute that owns the attribute option.
     */
    public function attribute()
    {
        return $this->belongsTo(AttributeProxy::modelClass());
    }

    /**
     * Get image url for the swatch value url.
     */
    public function swatch_value_url()
    {
        if ($this->swatch_value && $this->attribute->swatch_type == 'image') {
            return Storage::url($this->swatch_value);
        }
        
        return;
    }

    /**
     * Get image url for the product image.
     */
    public function getSwatchValueUrlAttribute()
    {
        return $this->swatch_value_url();
    }
}