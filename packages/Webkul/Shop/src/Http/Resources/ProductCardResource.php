<?php

namespace Webkul\Shop\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Webkul\Product\Helpers\Review;

class ProductCardResource extends JsonResource
{
    /**
     * Review helper instance.
     *
     * @var Review
     */
    protected $reviewHelper;

    /**
     * Create a new resource instance.
     *
     * @param  mixed  $resource
     * @return void
     */
    public function __construct($resource)
    {
        $this->reviewHelper = app(Review::class);

        parent::__construct($resource);
    }

    /**
     * Transform the resource into an array.
     *
     * This is a slim variant of {@see ProductResource} used for product
     * listings and carousels. The heavy `description` field and the full
     * `images` gallery are intentionally omitted - the product card never
     * renders them - which keeps listing payloads small and avoids
     * generating cached gallery image URLs for every product.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $productTypeInstance = $this->getTypeInstance();

        return [
            'id' => $this->id,
            'sku' => $this->sku,
            'name' => $this->name,
            'url_key' => $this->url_key,
            'base_image' => product_image()->getProductBaseImage($this),
            'is_new' => (bool) $this->new,
            'is_featured' => (bool) $this->featured,
            'on_sale' => (bool) $productTypeInstance->haveDiscount(),
            'is_saleable' => (bool) $productTypeInstance->isSaleable(),
            'is_wishlist' => (bool) auth()->guard()->user()?->wishlist_items
                ->where('channel_id', core()->getCurrentChannel()->id)
                ->where('product_id', $this->id)->count(),
            'min_price' => core()->formatPrice($productTypeInstance->getMinimalPrice()),
            'price_html' => $productTypeInstance->getPriceHtml(),
            'ratings' => [
                'average' => $this->reviewHelper->getAverageRating($this),
                'total' => $this->reviewHelper->getTotalRating($this),
            ],
            'reviews' => [
                'total' => $this->reviewHelper->getTotalReviews($this),
            ],
        ];
    }
}
