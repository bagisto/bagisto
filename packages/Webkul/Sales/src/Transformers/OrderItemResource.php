<?php

namespace Webkul\Sales\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderItemResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'product_id'           => $this->product_id,
            'product_type'         => get_class($this->product),
            'sku'                  => $this->sku,
            'type'                 => $this->type,
            'name'                 => $this->name,
            'weight'               => $this->weight,
            'total_weight'         => $this->total_weight,
            'qty_ordered'          => $this->parent_id ? ($this->quantity ?? 1) * $this->parent->quantity : ($this->quantity ?? 1),
            'price'                => $this->price,
            'base_price'           => $this->base_price,
            'total'                => $this->total,
            'base_total'           => $this->base_total,
            'tax_percent'          => $this->tax_percent,
            'tax_amount'           => $this->tax_amount,
            'base_tax_amount'      => $this->base_tax_amount,
            'tax_category_id'      => $this->tax_category_id,
            'discount_percent'     => $this->discount_percent,
            'discount_amount'      => $this->discount_amount,
            'base_discount_amount' => $this->base_discount_amount,
            'additional'           => array_merge($this->resource->additional ?? [], ['locale' => core()->getCurrentLocale()->code]),
            'children'             => self::collection($this->children)->jsonSerialize(),
        ];
    }
}
