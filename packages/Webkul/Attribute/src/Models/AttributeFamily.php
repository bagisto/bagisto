<?php

namespace Webkul\Attribute\Models;

use Illuminate\Database\Eloquent\Model;
use Webkul\Attribute\Models\Attribute;
use Webkul\Attribute\Models\AttributeGroup;

class AttributeFamily extends Model
{
    public $timestamps = false;
    
    protected $fillable = ['code', 'name'];

    /**
     * Get all of the attributes for the attribute groups.
     */
    public function attributes()
    {
        return $this->hasManyThrough(Attribute::class, AttributeGroup::class);
    }

    /**
     * Get all of the attribute groups.
     */
    public function attribute_groups()
    {
        return $this->hasMany(AttributeGroup::class)->orderBy('position');
    }
}