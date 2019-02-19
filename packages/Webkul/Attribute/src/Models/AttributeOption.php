<?php

namespace Webkul\Attribute\Models;

use Webkul\Core\Eloquent\TranslatableModel;
use Dimsav\Translatable\Translatable;
use Webkul\Attribute\Models\Attribute;
use Webkul\Attribute\Contracts\AttributeOption as AttributeOptionContract;

class AttributeOption extends TranslatableModel implements AttributeOptionContract
{
    public $timestamps = false;

    public $translatedAttributes = ['label'];

    protected $fillable = ['admin_name', 'sort_order'];

    /**
     * Get the attribute that owns the attribute option.
     */
    public function attribute()
    {
        return $this->model()->getCustomAttributesAttribute();
    }
}