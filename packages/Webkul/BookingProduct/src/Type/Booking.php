<?php

namespace Webkul\BookingProduct\Type;

use Webkul\Attribute\Repositories\AttributeRepository;
use Webkul\Product\Repositories\ProductRepository;
use Webkul\Product\Repositories\ProductAttributeValueRepository;
use Webkul\Product\Repositories\ProductInventoryRepository;
use Webkul\Product\Repositories\ProductImageRepository;
use Webkul\Product\Helpers\ProductImage;
use Webkul\BookingProduct\Repositories\BookingProductRepository;
use Webkul\BookingProduct\Helpers\Booking as BookingHelper;
use Webkul\Product\Type\Virtual;

class Booking extends Virtual
{
    /**
     * BookingProductRepository instance
     *
     * @var \Webkul\BookingProduct\Repositories\BookingProductRepository
     */
    protected $bookingProductRepository;

    /**
     * Booking helper instance
     *
     * @var \Webkul\BookingProduct\Helpers\Booking
     */
    protected $bookingHelper;

    /**
     * @var array
     */
    protected $additionalViews = [
        'admin::catalog.products.accordians.images',
        'admin::catalog.products.accordians.categories',
        'admin::catalog.products.accordians.channels',
        'bookingproduct::admin.catalog.products.accordians.booking',
        'admin::catalog.products.accordians.product-links',
    ];

    /**
     * Create a new product type instance.
     *
     * @param  \Webkul\Attribute\Repositories\AttributeRepository  $attributeRepository
     * @param  \Webkul\Product\Repositories\ProductRepository  $productRepository
     * @param  \Webkul\Product\Repositories\ProductAttributeValueRepository  $attributeValueRepository
     * @param  \Webkul\Product\Repositories\ProductInventoryRepository  $productInventoryRepository
     * @param  \Webkul\Product\Repositories\ProductImageRepository  $productImageRepository
     * @param  \Webkul\Product\Helpers\ProductImage $productImageHelper
     * @param  \Webkul\BookingProduct\Repositories\BookingProductRepository  $bookingProductRepository
     * @param  \Webkul\BookingProduct\Helpers\BookingHelper  $bookingHelper
     * @return void
     */
    public function __construct(
        AttributeRepository $attributeRepository,
        ProductRepository $productRepository,
        ProductAttributeValueRepository $attributeValueRepository,
        ProductInventoryRepository $productInventoryRepository,
        ProductImageRepository $productImageRepository,
        ProductImage $productImageHelper,
        BookingProductRepository $bookingProductRepository,
        BookingHelper $bookingHelper
    )
    {
        parent::__construct(
            $attributeRepository,
            $productRepository,
            $attributeValueRepository,
            $productInventoryRepository,
            $productImageRepository,
            $productImageHelper
        );

        $this->bookingProductRepository = $bookingProductRepository;

        $this->bookingHelper = $bookingHelper;
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

        return app($this->bookingHelper->getTypeHepler($bookingProduct->type))->isItemHaveQuantity($cartItem);
    }

    /**
     * Add product. Returns error message if can't prepare product.
     *
     * @param  array  $data
     * @return array
     */
    public function prepareForCart($data)
    {
        if (! isset($data['booking']) || ! count($data['booking'])) {
            return trans('shop::app.checkout.cart.integrity.missing_options');
        }

        $products = [];

        $bookingProduct = $this->getBookingProduct($data['product_id']);

        if ($bookingProduct->type == 'event') {
            foreach ($data['booking']['qty'] as $ticketId => $qty) {
                if (! $qty) {
                    continue;
                }

                $cartProducts = parent::prepareForCart([
                    'product_id' => $data['product_id'],
                    'quantity'   => $qty,
                    'booking'    => [
                        'ticket_id' => $ticketId,
                    ],
                ]);

                if (is_string($cartProducts)) {
                    return $cartProducts;
                }
                    
                $products = array_merge($products, $cartProducts);
            }
        } else {
            $products = parent::prepareForCart($data);
        }

        $typeHelper = app($this->bookingHelper->getTypeHepler($bookingProduct->type));

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
        if ($this->product->id != $options2['product_id']) {
            return false;
        }

        if (isset($options1['booking']) && isset($options2['booking'])) {
            return $options1['booking'] === $options2['booking'];
        } elseif (! isset($options1['booking'])) {
            return false;
        } elseif (! isset($options2['booking'])) {
            return false;
        }
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
     * @param  \Webkul\Checkout\Contracts\CartItem  $item
     * @return float
     */
    public function validateCartItem($item)
    {
        $bookingProduct = $this->getBookingProduct($item->product_id);

        if (! $bookingProduct) {
            return;
        }

        app($this->bookingHelper->getTypeHepler($bookingProduct->type))->validateCartItem($item);
    }
}