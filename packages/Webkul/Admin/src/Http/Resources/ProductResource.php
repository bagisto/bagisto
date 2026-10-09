<?php

namespace Webkul\Admin\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'type' => $this->type,
            'sku' => $this->sku,
            'name' => $this->name,
            'price' => $this->price,
            'formatted_price' => core()->formatPrice($this->price),
            'images' => $this->images,
            'inventories' => $this->inventories,
            'qty_available' => $this->resolveAvailableQuantity(),
            'is_options_required' => ! $this->getTypeInstance()->canBeAddedToCartWithoutOptions(),
            'is_saleable' => $this->getTypeInstance()->isSaleable(),
            'can_change_qty' => $this->getTypeInstance()->showQuantityBox(),
        ];
    }

    /**
     * The quantity available for sale, or null for a type that keeps no stock, so the panel can
     * say "unlimited" rather than a misleading zero.
     */
    protected function resolveAvailableQuantity(): ?int
    {
        if ($this->type === 'booking') {
            $bookingProduct = $this->booking_products->first();

            if (! $bookingProduct) {
                return 0;
            }

            if ($bookingProduct->type === 'event') {
                return (int) $bookingProduct->event_tickets->sum('qty');
            }

            return (int) ($bookingProduct->qty ?? 0);
        }

        if ($this->getTypeInstance()->isStockable()) {
            return (int) $this->inventories->sum('qty');
        }

        return null;
    }
}
