<?php

namespace Webkul\Attribute\Models;

use Webkul\Attribute\Contracts\AttributeGroup as AttributeGroupContract;
use Webkul\Core\Eloquent\TranslatableModel;

class AttributeGroup extends TranslatableModel implements AttributeGroupContract
{
    /**
     * The translatable attributes.
     *
     * @var array
     */
    public $translatedAttributes = ['name'];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'code',
        'admin_name',
        'column',
        'position',
        'is_user_defined',
    ];

    /**
     * The relations to eager load on every query.
     *
     * @var array
     */
    protected $with = ['translations'];

    /**
     * Get the attributes that owns the attribute group.
     */
    public function custom_attributes()
    {
        return $this->belongsToMany(AttributeProxy::modelClass(), 'attribute_group_mappings')
            ->withPivot('position')
            ->orderBy('pivot_position', 'asc');
    }
}
