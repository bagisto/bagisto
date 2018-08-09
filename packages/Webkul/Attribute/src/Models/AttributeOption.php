<?php

namespace Webkul\Attribute\Models;

use Webkul\Core\Eloquent\TranslatableModel;
use Dimsav\Translatable\Translatable;
use Webkul\Attribute\Models\Attribute;

class AttributeOption extends TranslatableModel
{
    public $timestamps = false;
    
    public $translatedAttributes = ['label'];

    protected $fillable = ['admin_name', 'sort_order'];

    /**
     * Get the attribute that owns the attribute option.
     */
    public function attribute()
    {
        return $this->belongsTo(Attribute::class);
    }
}