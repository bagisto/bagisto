<?php

namespace Webkul\PreOrder\Helpers;

use Webkul\Product\Repositories\ProductRepository;
use Carbon\Carbon;

/**
 * Product helper
 *
 * @author    Jitendra Singh <jitendra@webkul.com>
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class Product
{
    /**
     * ProductRepository object
     *
     * @var Product
    */
    protected $productRepository;

    /**
     * Create a new customer event listener instance.
     *
     * @param  Webkul\Product\Repositories\ProductRepository $productRepository
     * @return void
     */
    public function __construct(
        ProductRepository $productRepository
    )
    {
        $this->productRepository = $productRepository;
    }

    /**
     * Return preorder variants
     *
     * @param Product $product
     */
    public function getPreOrderVariants($product)
    {
        $config = [];

        foreach ($product->variants as $variant) {
            if ($variant->totalQuantity() < 1 && $variant->allow_preorder) {
                $config[$variant->product_id] = [
                    'preorder_qty' => $variant->preorder_qty,
                    'availability_text' => $variant->preorder_availability && Carbon::parse($variant->preorder_availability) > Carbon::now()
                        ? trans('preorder::app.shop.products.available-on', [
                                'date' => core()->formatDate(Carbon::parse($variant->preorder_availability), 'F d, Y')
                            ])
                        : null
                ];
            }
        }

        return $config;
    }
}