<?php

namespace Webkul\Product\Type;

use Carbon\Carbon;
use Illuminate\Support\Arr;
use Webkul\Attribute\Repositories\AttributeRepository;
use Webkul\BookingProduct\Helpers\Booking as BookingHelper;
use Webkul\BookingProduct\Repositories\BookingProductRepository;
use Webkul\Checkout\Models\CartItem;
use Webkul\Customer\Repositories\CustomerRepository;
use Webkul\Product\DataTypes\CartItemValidationResult;
use Webkul\Product\Helpers\Indexers\Price\Virtual as VirtualIndexer;
use Webkul\Product\Repositories\ProductAttributeValueRepository;
use Webkul\Product\Repositories\ProductCustomerGroupPriceRepository;
use Webkul\Product\Repositories\ProductImageRepository;
use Webkul\Product\Repositories\ProductInventoryRepository;
use Webkul\Product\Repositories\ProductRepository;
use Webkul\Product\Repositories\ProductVideoRepository;

class Booking extends AbstractType
{
    /**
     * Skip attribute for downloadable product type.
     *
     * @var array
     */
    protected $skipAttributes = [
        'length',
        'width',
        'height',
        'weight',
        'depth',
        'manage_stock',
        'guest_checkout',
    ];

    /**
     * Is a composite product type.
     *
     * @var bool
     */
    protected $isComposite = true;

    /**
     * Is a stockable product type.
     *
     * @var bool
     */
    protected $isStockable = false;

    /**
     * Create a new product type instance.
     *
     * @return void
     */
    public function __construct(
        protected CustomerRepository $customerRepository,
        protected AttributeRepository $attributeRepository,
        protected ProductRepository $productRepository,
        protected ProductAttributeValueRepository $attributeValueRepository,
        protected ProductInventoryRepository $productInventoryRepository,
        protected ProductImageRepository $productImageRepository,
        protected ProductVideoRepository $productVideoRepository,
        protected ProductCustomerGroupPriceRepository $productCustomerGroupPriceRepository,
        protected BookingProductRepository $bookingProductRepository,
        protected BookingHelper $bookingHelper
    ) {}

    /**
     * @param  int  $id
     * @param  string  $attribute
     * @return \Webkul\Product\Contracts\Product
     */
    public function update(array $data, $id, $attribute = 'id')
    {
        $product = parent::update($data, $id, $attribute);

        if (request()->route()->getName() != 'admin.catalog.products.mass_update') {
            $bookingProduct = $this->bookingProductRepository->findOneByField('product_id', $id);

            $bookingProduct
                ? $this->bookingProductRepository->update($data['booking'], $bookingProduct->id)
                : $this->bookingProductRepository->create(array_merge($data['booking'], [
                    'product_id' => $id,
                ]));
        }

        return $product;
    }

    /**
     * Returns additional views
     *
     * @return mixed
     */
    public function getBookingProduct(int $productId)
    {
        $bookingProducts = [];

        if (isset($bookingProducts[$productId])) {
            return $bookingProducts[$productId];
        }

        return $bookingProducts[$productId] = $this->bookingProductRepository->findOneByField('product_id', $productId);
    }

    /**
     * Return true if this product can have inventory
     */
    public function showQuantityBox(): bool
    {
        $bookingProduct = $this->getBookingProduct($this->product->id);

        return in_array($bookingProduct->type, ['default', 'rental', 'table']);
    }

    /**
     * @param  \Webkul\Checkout\Contracts\CartItem  $cartItem
     */
    public function isItemHaveQuantity($cartItem): bool
    {
        $bookingProduct = $this->getBookingProduct($this->product->id);

        return app($this->bookingHelper->getTypeHelper($bookingProduct->type))->isItemHaveQuantity($cartItem);
    }

    public function haveSufficientQuantity(int $qty): bool
    {
        return true;
    }

    /**
     * Return true if this product can be composite.
     *
     * @return bool
     */
    public function isComposite()
    {
        return $this->isComposite;
    }

    /**
     * Add product. Returns error message if can't prepare product.
     *
     * @param  array  $data
     * @return array
     */
    public function prepareForCart($data)
    {
        if (empty($data['booking'])) {
            return trans('shop::app.products.booking.cart.integrity.missing_options');
        }

        $products = [];

        $bookingProduct = $this->getBookingProduct($data['product_id']);

        if ($bookingProduct->type == 'rental') {
            if (isset($data['booking']['slot']['from'])) {
                $time = $data['booking']['slot']['to'] - $data['booking']['slot']['from'];

                $hours = floor($time / 60) / 60;

                if ($hours > 1) {
                    return trans('shop::app.products.booking.cart.integrity.select_hourly_duration');
                }
            }

            $products = parent::prepareForCart($data);
        } elseif ($bookingProduct->type == 'event') {
            if (
                Carbon::now() > $bookingProduct->available_from
                && Carbon::now() > $bookingProduct->available_to
            ) {
                return trans('shop::app.products.booking.cart.integrity.event.expired');
            }

            $filtered = Arr::where($data['booking']['qty'], function ($qty, $key) {
                return $qty != 0;
            });

            if (! count($filtered)) {
                return trans('shop::app.products.booking.cart.integrity.missing_options');
            }

            $cartProductsList = [];

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

                $cartProductsList[] = $cartProducts;
            }

            $products = array_merge(...$cartProductsList);
        } else {
            $products = parent::prepareForCart($data);
        }

        $typeHelper = app($this->bookingHelper->getTypeHelper($bookingProduct->type));

        if (! $typeHelper->isSlotAvailable($products)) {
            return trans('shop::app.products.booking.cart.integrity.inventory_warning');
        }

        $products = $typeHelper->addAdditionalPrices($products);

        return $products;
    }

    /**
     * @param  array  $options1
     * @param  array  $options2
     */
    public function compareOptions($options1, $options2): bool
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
     */
    public function getAdditionalOptions($data): array
    {
        return $this->bookingHelper->getCartItemOptions($data);
    }

    /**
     * Validate cart item product price
     */
    public function validateCartItem(CartItem $item): CartItemValidationResult
    {
        $result = new CartItemValidationResult;

        if (parent::isCartItemInactive($item)) {
            $result->itemIsInactive();

            return $result;
        }

        if (! $bookingProduct = $this->getBookingProduct($item->product_id)) {
            $result->cartIsInvalid();

            return $result;
        }

        return app($this->bookingHelper->getTypeHelper($bookingProduct->type))->validateCartItem($item);
    }

    /**
     * Returns price indexer class for a specific product type
     *
     * @return string
     */
    public function getPriceIndexer()
    {
        return app(VirtualIndexer::class);
    }
}
