<?php

namespace Webkul\Shop\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class CartItemResource extends JsonResource
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
            'id'                        => $this->id,
            'quantity'                  => $this->quantity,
            'type'                      => $this->type,
            'name'                      => $this->name,
            'price'                     => $this->price,
            'formatted_price'           => core()->formatPrice($this->price),
            'price_incl_tax'            => $this->price_incl_tax,
            'formatted_price_incl_tax'  => core()->formatPrice($this->price_incl_tax),
            'total'                     => $this->total,
            'formatted_total'           => core()->formatPrice($this->total),
            'total_incl_tax'            => $this->total_incl_tax,
            'formatted_total_incl_tax'  => core()->formatPrice($this->total_incl_tax),
            'discount_amount'           => $this->discount_amount,
            'formatted_discount_amount' => core()->formatPrice($this->discount_amount),
            'base_image'                => $this->getTypeInstance()->getBaseImage($this),
            'product_url_key'           => $this->product->url_key,
            'options'                   => $this->formatAdditionalAttributes(),
        ];
    }

    /**
     * Format the additional attributes.
     */
    public function formatAdditionalAttributes(): array
    {
        $attributes = $this->resource->additional['attributes'] ?? [];

        if (! empty($attributes)) {
            return collect($attributes)
                ->map(function ($attribute) {
                    if (
                        isset($attribute['attribute_type'])
                        && $attribute['attribute_type'] == 'file'
                    ) {
                        $attribute['file_name'] = File::basename($attribute['option_label']);

                        $attribute['file_url'] = Storage::url($attribute['option_label']);
                    }

                    return $attribute;
                })
                ->toArray();
        }

        return [];
    }
}
