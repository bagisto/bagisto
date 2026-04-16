<?php

namespace Webkul\CatalogRule\Models;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Webkul\CatalogRule\Contracts\CatalogRule as CatalogRuleContract;
use Webkul\CatalogRule\Database\Factories\CatalogRuleFactory;
use Webkul\Core\Models\ChannelProxy;
use Webkul\Customer\Models\CustomerGroupProxy;

class CatalogRule extends Model implements CatalogRuleContract
{
    use HasFactory;

    /**
     * Add fillable property to the model.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'description',
        'starts_from',
        'ends_till',
        'status',
        'condition_type',
        'conditions',
        'end_other_rules',
        'action_type',
        'discount_amount',
        'sort_order',
    ];

    /**
     * Cast the conditions to the array.
     *
     * @var array
     */
    protected $casts = [
        'conditions' => 'array',
        'status' => 'boolean',
        'condition_type' => 'integer',
        'end_other_rules' => 'boolean',
        'sort_order' => 'integer',
    ];

    /**
     * Set status with proper boolean conversion.
     */
    public function setStatusAttribute($value): void
    {
        $this->attributes['status'] = filter_var($value, FILTER_VALIDATE_BOOLEAN);
    }

    /**
     * Set end other rules with proper boolean conversion.
     */
    public function setEndOtherRulesAttribute($value): void
    {
        $this->attributes['end_other_rules'] = filter_var($value, FILTER_VALIDATE_BOOLEAN);
    }

    /**
     * Set starts from with empty string to null conversion.
     */
    public function setStartsFromAttribute($value): void
    {
        $this->attributes['starts_from'] = $value !== '' && $value !== null ? $value : null;
    }

    /**
     * Set ends till with empty string to null conversion.
     */
    public function setEndsTillAttribute($value): void
    {
        $this->attributes['ends_till'] = $value !== '' && $value !== null ? $value : null;
    }

    /**
     * Get the channels that owns the catalog rule.
     */
    public function channels(): BelongsToMany
    {
        return $this->belongsToMany(ChannelProxy::modelClass(), 'catalog_rule_channels');
    }

    /**
     * Get the customer groups that owns the catalog rule.
     */
    public function customer_groups(): BelongsToMany
    {
        return $this->belongsToMany(CustomerGroupProxy::modelClass(), 'catalog_rule_customer_groups');
    }

    /**
     * Get the Catalog rule Product that owns the catalog rule
     */
    public function catalog_rule_products(): HasMany
    {
        return $this->hasMany(CatalogRuleProductProxy::modelClass());
    }

    /**
     * Get the Catalog rule Product that owns the catalog rule.
     */
    public function catalog_rule_product_prices(): HasMany
    {
        return $this->hasMany(CatalogRuleProductPriceProxy::modelClass());
    }

    /**
     * Create a new factory instance for the model.
     */
    protected static function newFactory(): Factory
    {
        return CatalogRuleFactory::new();
    }
}
