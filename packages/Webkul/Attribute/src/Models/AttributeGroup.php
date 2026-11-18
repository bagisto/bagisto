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
     * Read the admin name, falling back to the name column for a row written before the admin name existed.
     */
    public function getAdminNameAttribute(?string $value): ?string
    {
        return $value ?? ($this->attributes['name'] ?? null);
    }

    /**
     * Write the admin name, and into the deprecated name column too, for queries written against it.
     */
    public function setAdminNameAttribute(?string $value): void
    {
        $this->attributes['admin_name'] = $value;

        $this->attributes['name'] = $value;
    }

    /**
     * Convert the model's attributes to an array, the name never empty for a locale the group is not named in.
     *
     * @return array
     */
    public function attributesToArray()
    {
        $attributes = parent::attributesToArray();

        $attributes['name'] ??= $this->admin_name;

        return $attributes;
    }

    /**
     * Fill the model, taking a name given without an admin name as both, the way a group was named before.
     *
     * @return $this
     */
    public function fill(array $attributes)
    {
        if (
            is_string($attributes['name'] ?? null)
            && ! array_key_exists('admin_name', $attributes)
        ) {
            $attributes['admin_name'] = $attributes['name'];
        }

        return parent::fill($attributes);
    }

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
