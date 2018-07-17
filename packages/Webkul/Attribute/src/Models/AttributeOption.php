<?php

namespace Webkul\Attribute\Models;

use Illuminate\Database\Eloquent\Model;
use Dimsav\Translatable\Translatable;
use Webkul\Attribute\Models\Attribute;

class AttributeOption extends Model
{
    public $timestamps = false;
    
    use Translatable;
    
    public $translatedAttributes = ['label'];

    protected $fillable = ['sort_order'];

    /**
     * Get the attribute that owns the attribute option.
     */
    public function attribute()
    {
        return $this->belongsTo(Attribute::class);
    }
}