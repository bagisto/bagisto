<?php

namespace Webkul\BookingProduct\Type;

use Carbon\Carbon;
use Illuminate\Support\Arr;
use Webkul\Attribute\Repositories\AttributeRepository;
use Webkul\BookingProduct\Helpers\Booking as BookingHelper;
use Webkul\BookingProduct\Repositories\BookingProductRepository;
use Webkul\Checkout\Models\CartItem;
use Webkul\Product\Datatypes\CartItemValidationResult;
use Webkul\Product\Repositories\ProductAttributeValueRepository;
use Webkul\Product\Repositories\ProductImageRepository;
use Webkul\Product\Repositories\ProductVideoRepository;
use Webkul\Product\Repositories\ProductInventoryRepository;
use Webkul\Product\Repositories\ProductRepository;
use Webkul\Product\Type\Virtual;

class Booking extends Virtual
{
    /** @var bool 
     * 
     * do not allow booking products to be copied, it would be too complicated. 
     * 
     */
    protected $canBeCopied = false;

    /**
     * @var array
     */
    protected $additionalViews = [
        'admin::catalog.products.accordians.images',
        'admin::catalog.products.accordians.categories',
        'admin::catalog.products.accordians.channels',
        'bookingproduct::admin.catalog.products.accordians.booking',
        'admin::catalog.products.accordians.product-links',
        'admin::catalog.products.accordians.videos',
    ];

    /**
     * Create a new product type instance.
     *
     * @param  \Webkul\Attribute\Repositories\AttributeRepository  $attributeRepository
     * @param  \Webkul\Product\Repositories\ProductRepository  $productRepository
     * @param  \Webkul\Product\Repositories\ProductAttributeValueRepository  $attributeValueRepository
     * @param  \Webkul\Product\Repositories\ProductInventoryRepository  $productInventoryRepository
     * @param  \Webkul\Product\Repositories\ProductImageRepository  $productImageRepository
     * @param  \Webkul\BookingProduct\Repositories\BookingProductRepository  $bookingProductRepository
     * @param  \Webkul\BookingProduct\Helpers\BookingHelper  $bookingHelper
     * @param  \Webkul\Product\Repositories\ProductVideoRepository  $productVideoRepository
     * @return void
     */
    public function __construct(
        AttributeRepository $attributeRepository,
        ProductRepository $productRepository,
        ProductAttributeValueRepository $attributeValueRepository,
        ProductInventoryRepository $productInventoryRepository,
        ProductImageRepository $productImageRepository,
        ProductVideoRepository $productVideoRepository,
        protected BookingProductRepository $bookingProductRepository,
        protected BookingHelper $bookingHelper
    )
    {
        parent::__construct(
            $attributeRepository,
            $productRepository,
            $attributeValueRepository,
            $productInventoryRepository,
            $productImageRepository,
            $productVideoRepository
        );
    }

    /**
     * @param  array  $data
     * @param  int  $id
     * @param  string  $attribute
     * @return \Webkul\Product\Contracts\Product
     */
    public function update(array $data, $id, $attribute = "id")
    {
        $product = parent::update($data, $id, $attribute);

        if (request()->route()->getName() != 'admin.catalog.products.massupdate') {
            $bookingProduct = $this->bookingProductRepository->findOneByField('product_id', $id);

            if ($bookingProduct) {
                $this->bookingProductRepository->update(request('booking'), $bookingProduct->id);
            } else {
                $this->bookingProductRepository->create(array_merge(request('booking'), [
                    'product_id' => $id,
                ]));
            }
        }

        return $product;
    }

    /**
     * Returns additional views
     *
     * @param  int  $id
     * @return array
     */
    public function getBookingProduct($productId)
    {
        static $bookingProducts = [];

        if (isset($bookingProducts[$productId])) {
            return $bookingProducts[$productId];
        }

        return $bookingProducts[$productId] = $this->bookingProductRepository->findOneByField('product_id', $productId);
    }

    /**
     * Return true if this product can have inventory
     *
     * @return bool
     */
    public function showQuantityBox()
    {
        $bookingProduct = $this->getBookingProduct($this->product->id);

        if (! $bookingProduct) {
            return false;
        }

        if (in_array($bookingProduct->type, ['default', 'rental', 'table'])) {
            return true;
        }

        return false;
    }

    /**
     * @param  \Webkul\Checkout\Contracts\CartItem  $cartItem
     * @return bool
     */
    public function isItemHaveQuantity($cartItem)
    {
        $bookingProduct = $this->getBookingProduct($this->product->id);

        return app($this->bookingHelper->getTypeHelper($bookingProduct->type))->isItemHaveQuantity($cartItem);
    }

    /**
     * @param  int  $qty
     * @return bool
     */
    public function haveSufficientQuantity(int $qty): bool
    {
        return true;
    }

    /**
     * Add product. Returns error message if can't prepare product.
     *
     * @param  array  $data
     * @return array
     */
    public function prepareForCart($data)
    {
        if (
            ! isset($data['booking'])
            || ! count($data['booking'])
        ) {
            return trans('shop::app.checkout.cart.integrity.missing_options');
        }

        $products = [];

        $bookingProduct = $this->getBookingProduct($data['product_id']);


        if ($bookingProduct->type == 'rental') {
            if (isset($data['booking']['slot']['from'])) {
                $time = $data['booking']['slot']['to'] - $data['booking']['slot']['from'];
                $hours = floor($time / 60) / 60;

                if ($hours > 1) {
                    return trans('shop::app.checkout.cart.integrity.select_hourly_duration');
                }
            }

            $products = parent::prepareForCart($data);
        } elseif ($bookingProduct->type == 'event') {
            if (
                Carbon::now() > $bookingProduct->available_from
                && Carbon::now() > $bookingProduct->available_to
            ) {
                return trans('shop::app.checkout.cart.event.expired');
            }

            $filtered = Arr::where($data['booking']['qty'], function ($qty, $key) {
                return $qty != 0;
            });

            if (! count($filtered)) {
                return trans('shop::app.checkout.cart.integrity.missing_options');
            }

            foreach ($data['booking']['qty'] as $ticketId => $qty) {
                if (! $qty) {
                    continue;
                }

                $data['quantity'] = $qty;
                $data['booking']['ticket_id'] = $ticketId;
                $data['booking']['slot'] = implode('-', [$bookingProduct->available_from->timestamp, $bookingProduct->available_to->timestamp]);
                $cartProducts = parent::prepareForCart($data);

                if (is_string($cartProducts)) {
                    return $cartProducts;
                }

                $products = array_merge($products, $cartProducts);
            }
        } else {
            $products = parent::prepareForCart($data);
        }

        $typeHelper = app($this->bookingHelper->getTypeHelper($bookingProduct->type));

        if (! $typeHelper->isSlotAvailable($products)) {
            return trans('shop::app.checkout.cart.quantity.inventory_warning');
        }

        $products = $typeHelper->addAdditionalPrices($products);

        return $products;
    }

    /**
     *
     * @param  array  $options1
     * @param  array  $options2
     * @return boolean
     */
    public function compareOptions($options1, $options2)
    {
        if ($this->product->id !== (int) $options2['product_id']) {
            return false;
        }

        if (
            isset($options1['booking'], $options2['booking'])
            && isset($options1['booking']['ticket_id'], $options2['booking']['ticket_id'])
            && $options1['booking']['ticket_id'] === $options2['booking']['ticket_id']
        ) {
            return true;
        }

        return false;
    }

    /**
     * Returns additional information for items
     *
     * @param  array  $data
     * @return array
     */
    public function getAdditionalOptions($data)
    {
        return $this->bookingHelper->getCartItemOptions($data);
    }

    /**
     * Validate cart item product price
     *
     * @param \Webkul\Checkout\Models\CartItem $item
     *
     * @return \Webkul\Product\Datatypes\CartItemValidationResult
     */
    public function validateCartItem(CartItem $item): CartItemValidationResult
    {
        $result = new CartItemValidationResult();

        if (parent::isCartItemInactive($item)) {
            $result->itemIsInactive();

            return $result;
        }

        $bookingProduct = $this->getBookingProduct($item->product_id);

        if (! $bookingProduct) {
            $result->cartIsInvalid();
            return $result;
        }

        return app($this->bookingHelper->getTypeHelper($bookingProduct->type))->validateCartItem($item);
    }
}
