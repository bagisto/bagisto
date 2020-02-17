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
use Webkul\Product\Type\AbstractType;

/**
 * Class Booking.
 *
 * @author    Jitendra Singh <jitendra@webkul.com>
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class Booking extends AbstractType
{
    /**
     * BookingProductRepository instance
     *
     * @var BookingProductRepository
    */
    protected $bookingProductRepository;

    /**
     * Booking helper instance
     *
     * @var Booking
    */
    protected $bookingHelper;

    /**
     * Skip attribute for virtual product type
     *
     * @var array
     */
    protected $skipAttributes = ['width', 'height', 'depth', 'weight'];

    /**
     * Show quantity box
     *
     * @var boolean
     */
    protected $showQuantityBox = true;

    /**
     * @var array
     */
    protected $additionalViews = [
        'admin::catalog.products.accordians.images',
        'admin::catalog.products.accordians.categories',
        'bookingproduct::admin.catalog.products.accordians.booking',
        'admin::catalog.products.accordians.product-links'
    ];

    /**
     * Create a new product type instance.
     *
     * @param  Webkul\Attribute\Repositories\AttributeRepository           $attributeRepository
     * @param  Webkul\Product\Repositories\ProductRepository               $productRepository
     * @param  Webkul\Product\Repositories\ProductAttributeValueRepository $attributeValueRepository
     * @param  Webkul\Product\Repositories\ProductInventoryRepository      $productInventoryRepository
     * @param  Webkul\Product\Repositories\ProductImageRepository          $productImageRepository
     * @param  Webkul\Product\Helpers\ProductImage                         $productImageHelper
     * @param  Webkul\BookingProduct\Repositories\BookingProductRepository $bookingProductRepository
     * @param  Webkul\BookingProduct\Helpers\BookingHelper                 $bookingHelper
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
     * Return true if this product type is saleable
     *
     * @return array
     */
    public function isSaleable()
    {
        if (! $this->product->status)
            return false;
            
        return true;
    }

    /**
     * Return true if this product can have inventory
     *
     * @return array
     */
    public function isStockable()
    {
        return false;
    }

    /**
     * Return true if item can be moved to cart from wishlist
     *
     * @param CartItem $item
     * @return boolean
     */
    public function canBeMovedFromWishlistToCart($item)
    {
        return true;
    }

    /**
     * Add product. Returns error message if can't prepare product.
     *
     * @param array $data
     * @return array
     */
    public function prepareForCart($data)
    {
        if (! isset($data['booking']) || ! count($data['booking']))
            return trans('shop::app.checkout.cart.integrity.missing_options');

        $products = parent::prepareForCart($data);
        
        $bookingProduct = $this->bookingProductRepository->findOneByField('product_id', $data['product_id']);

        if ($bookingProduct)
            $products = app($this->bookingHelper->getTypeHepler($bookingProduct->type))->addAdditionalPrices($products);

        return $products;
    }

    /**
     *
     * @param array $options1
     * @param array $options2
     * @return boolean
     */
    public function compareOptions($options1, $options2)
    {
        if ($this->product->id != $options2['product_id'])
            return false;

        return $options1['booking'] == $options2['booking'];
    }

    /**
     * Returns additional information for items
     *
     * @param array $data
     * @return array
     */
    public function getAdditionalOptions($data)
    {
        return $this->bookingHelper->getCartItemOptions($data);
    }

    /**
     * Validate cart item product price
     *
     * @param CartItem $item
     * @return float
     */
    public function validateCartItem($item)
    {
        $bookingProduct = $this->bookingProductRepository->findOneByField('product_id', $item->product_id);

        if (! $bookingProduct)
            return;

        app($this->bookingHelper->getTypeHepler($bookingProduct->type))->validateCartItem($item);
    }
}