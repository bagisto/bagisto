<?php

namespace Webkul\BookingProduct\Listeners;

use Webkul\BookingProduct\Repositories\BookingProductRepository;

/**
 * Product Event handler
 *
 * @author    Jitendra Singh <jitendra@webkul.com>
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class Product
{
    /**
     * BookingProductRepository Object
     *
     * @var Object
     */
    protected $bookingProductRepository;

    /**
     * Create a new listener instance.
     *
     * @param  Webkul\BookingProduct\Repositories\BookingProductRepository $bookingProductRepository
     * @return void
     */
    public function __construct(BookingProductRepository $bookingProductRepository)
    {
        $this->bookingProductRepository = $bookingProductRepository;
    }

    /**
     * After the product is updated
     *
     * @return void
     */
    public function afterProductUpdated($product)
    {
        if ($product->type != 'booking' || ! request('booking'))
            return;

        $bookingProduct = $this->bookingProductRepository->findOneByField('product_id', $product->id);

        if ($bookingProduct) {
            $this->bookingProductRepository->update(request('booking'), $bookingProduct->id);
        } else {
            $this->bookingProductRepository->create(array_merge(request('booking'), [
                    'product_id' => $product->id
                ]));
        }
    }
}