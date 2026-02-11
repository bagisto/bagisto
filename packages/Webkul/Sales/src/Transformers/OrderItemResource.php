<?php

namespace Webkul\Sales\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\DB;

class OrderItemResource extends JsonResource
{
    /**
     * Indicates if the resource's collection keys should be preserved.
     *
     * @var bool
     */
    public $preserveKeys = true;

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'product_id' => $this->product_id,
            'product_type' => get_class($this->product),
            'sku' => $this->sku,
            'type' => $this->type,
            'name' => $this->name,
            'weight' => $this->weight,
            'total_weight' => $this->total_weight,
            'qty_ordered' => $this->parent_id ? ($this->quantity ?? 1) * $this->parent->quantity : ($this->quantity ?? 1),
            'price' => $this->price,
            'price_incl_tax' => $this->price_incl_tax,
            'base_price' => $this->base_price,
            'base_price_incl_tax' => $this->base_price_incl_tax,
            'total' => $this->total,
            'total_incl_tax' => $this->total_incl_tax,
            'base_total' => $this->base_total,
            'base_total_incl_tax' => $this->base_total_incl_tax,
            'tax_percent' => $this->tax_percent,
            'tax_amount' => $this->tax_amount,
            'base_tax_amount' => $this->base_tax_amount,
            'tax_category_id' => $this->tax_category_id,
            'discount_percent' => $this->discount_percent,
            'discount_amount' => $this->discount_amount,
            'base_discount_amount' => $this->base_discount_amount,
            'additional' => array_merge($this->resource->additional ?? [], ['locale' => core()->getCurrentLocale()->code]),
            'rma_return_period' => $this->resolveRmaReturnPeriod(),
            'children' => self::collection($this->children)->jsonSerialize(),
        ];
    }

    /**
     * Resolve the RMA return period for this cart item at checkout time.
     *
     * Snapshots the effective return window so that future changes to RMA
     * rules or global configuration do not affect already placed orders.
     *
     * Returns null if the product does not have allow_rma explicitly enabled
     * or its type is not in the allowed product types for RMA.
     */
    protected function resolveRmaReturnPeriod(): ?int
    {
        if (! $this->product_id) {
            return null;
        }

        $allowedProductTypes = once(function () {
            $config = core()->getConfigData('sales.rma.setting.select_allowed_product_type');

            return $config ? array_map('trim', explode(',', $config)) : [];
        });

        /**
         * If allowed product types is configured, only products with types in the allowed list are eligible for RMA.
         */
        if (empty($allowedProductTypes) || ! in_array($this->type, $allowedProductTypes)) {
            return null;
        }

        $allowRmaAttrId = once(fn () => DB::table('attributes')->where('code', 'allow_rma')->value('id'));

        $rmaRuleAttrId = once(fn () => DB::table('attributes')->where('code', 'rma_rule_id')->value('id'));

        /**
         * If the product does not have the necessary RMA attributes, it is not eligible for RMA. This also avoids
         * potential errors when trying to fetch attribute values later.
         */
        if (! $allowRmaAttrId || ! $rmaRuleAttrId) {
            return null;
        }

        $allowRma = DB::table('product_attribute_values')
            ->where('product_id', $this->product_id)
            ->where('attribute_id', $allowRmaAttrId)
            ->value('boolean_value');

        /**
         * RMA is only applicable when allow_rma is explicitly set to 1.
         */
        if ($allowRma != 1) {
            return null;
        }

        /**
         * Check for a product-specific active RMA rule.
         */
        $returnPeriod = DB::table('product_attribute_values')
            ->where('product_id', $this->product_id)
            ->where('attribute_id', $rmaRuleAttrId)
            ->join('rma_rules', function ($join) {
                $join->on('product_attribute_values.integer_value', '=', 'rma_rules.id')
                    ->where('rma_rules.status', 1);
            })
            ->value('rma_rules.return_period');

        return (int) ($returnPeriod ?? core()->getConfigData('sales.rma.setting.default_allow_days'));
    }
}
