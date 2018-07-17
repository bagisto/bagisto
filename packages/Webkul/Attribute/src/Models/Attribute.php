<?php

namespace Webkul\Attribute\Models;

use Illuminate\Database\Eloquent\Model;
use Dimsav\Translatable\Translatable;
use Webkul\Attribute\Models\AttributeOption;

class Attribute extends Model
{
    use Translatable;
    
    public $translatedAttributes = ['name'];

    protected $fillable = ['code', 'admin_name', 'type', 'is_required', 'is_unique', 'value_per_locale', 'value_per_channel', 'is_filterable', 'is_configurable'];

    /**
     * Get the options.
     */
    public function options()
    {
        return $this->hasMany(AttributeOption::class);
    }
}