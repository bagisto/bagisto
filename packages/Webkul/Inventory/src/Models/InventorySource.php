<?php

namespace Webkul\Inventory\Models;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Webkul\Inventory\Contracts\InventorySource as InventorySourceContract;
use Webkul\Inventory\Database\Factories\InventorySourceFactory;

class InventorySource extends Model implements InventorySourceContract
{
    use HasFactory;

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['_token'];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'priority' => 'integer',
        'latitude' => 'decimal:5',
        'longitude' => 'decimal:5',
        'status' => 'boolean',
    ];

    /**
     * Set priority with fallback to default.
     */
    public function setPriorityAttribute($value): void
    {
        $this->attributes['priority'] = $value !== '' && $value !== null ? (int) $value : 0;
    }

    /**
     * Set latitude with empty string to null conversion.
     */
    public function setLatitudeAttribute($value): void
    {
        $this->attributes['latitude'] = $value !== '' && $value !== null ? $value : null;
    }

    /**
     * Set longitude with empty string to null conversion.
     */
    public function setLongitudeAttribute($value): void
    {
        $this->attributes['longitude'] = $value !== '' && $value !== null ? $value : null;
    }

    /**
     * Create a new factory instance for the model.
     */
    protected static function newFactory(): Factory
    {
        return InventorySourceFactory::new();
    }
}
