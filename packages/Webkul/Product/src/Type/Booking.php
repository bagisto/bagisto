<?php

namespace Webkul\Product\Type;

use Carbon\Carbon;
use Illuminate\Support\Arr;
use Webkul\Attribute\Repositories\AttributeRepository;
use Webkul\BookingProduct\Helpers\Booking as BookingHelper;
use Webkul\BookingProduct\Repositories\BookingProductRepository;
use Webkul\Checkout\Models\CartItem;
use Webkul\Customer\Repositories\CustomerRepository;
use Webkul\Product\Contracts\Product;
use Webkul\Product\DataTypes\CartItemValidationResult;
use Webkul\Product\Exceptions\InsufficientProductInventoryException;
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
    protected $isComposite = false;

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
     * @return Product
     */
    public function update(array $data, $id, $attribute = 'id')
    {
        $product = parent::update($data, $id, $attribute);

        if (request()->route()->getName() != 'admin.catalog.products.mass_update') {
            if (
                isset($data['booking']['type'])
                && $data['booking']['type'] != 'event'
            ) {
                if (! empty($data['booking']['available_from']) && strlen($data['booking']['available_from']) <= 10) {
                    $data['booking']['available_from'] = $data['booking']['available_from'].' 00:00:00';
                }

                if (! empty($data['booking']['available_to']) && strlen($data['booking']['available_to']) <= 10) {
                    $data['booking']['available_to'] = $data['booking']['available_to'].' 23:59:59';
                }
            }

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
     *
     * @throws InsufficientProductInventoryException
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
            if (Carbon::now() > $bookingProduct->available_to) {
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
            if ($bookingProduct->type == 'event') {
                foreach ($products as $product) {
                    if ($typeHelper->isItemHaveQuantity($product)) {
                        continue;
                    }

                    $ticket = $bookingProduct->event_tickets()->find($product['additional']['booking']['ticket_id']);

                    $ticketName = $ticket?->name ?? '';

                    $available = $typeHelper->getAvailableTicketQuantity($product);

                    $message = $available > 0
                        ? trans('shop::app.products.booking.cart.integrity.event.ticket_exceeds_available', [
                            'ticket' => $ticketName,
                            'qty'    => $available,
                        ])
                        : trans('shop::app.products.booking.cart.integrity.event.ticket_sold_out', [
                            'ticket' => $ticketName,
                        ]);

                    throw new InsufficientProductInventoryException($message);
                }
            }

            $messageKey = match ($bookingProduct->type) {
                'rental' => 'shop::app.products.booking.cart.integrity.rental_unavailable',
                default  => 'shop::app.products.booking.cart.integrity.inventory_warning',
            };

            throw new InsufficientProductInventoryException(trans($messageKey));
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

    /**
     * Override product prices to show a price range (base + cheapest ticket) to (base + most expensive ticket) for event bookings.
     */
    public function getProductPrices()
    {
        $bookingProduct = $this->getBookingProduct($this->product->id);

        if (
            $bookingProduct
            && $bookingProduct->type === 'event'
            && $bookingProduct->event_tickets->count()
        ) {
            $helper = app($this->bookingHelper->getTypeHelper('event'));

            $regularPrices = [];
            $finalPrices = [];

            foreach ($bookingProduct->event_tickets as $ticket) {
                $regularPrices[] = (float) $ticket->price;

                $finalPrices[] = $helper->isInSale($ticket)
                    ? (float) $ticket->special_price
                    : (float) $ticket->price;
            }

            $baseRegular = (float) $this->product->price;
            $baseFinal = (float) parent::getMinimalPrice();

            $fromRegular = $baseRegular + min($regularPrices);
            $fromFinal = $baseFinal + min($finalPrices);
            $toRegular = $baseRegular + max($regularPrices);
            $toFinal = $baseFinal + max($finalPrices);

            return [
                'from' => [
                    'regular' => [
                        'price'           => core()->convertPrice($fromRegular),
                        'formatted_price' => core()->currency($fromRegular),
                    ],

                    'final' => [
                        'price'           => core()->convertPrice($fromFinal),
                        'formatted_price' => core()->currency($fromFinal),
                    ],
                ],

                'to' => [
                    'regular' => [
                        'price'           => core()->convertPrice($toRegular),
                        'formatted_price' => core()->currency($toRegular),
                    ],
                    
                    'final' => [
                        'price'           => core()->convertPrice($toFinal),
                        'formatted_price' => core()->currency($toFinal),
                    ],
                ],
            ];
        }

        return parent::getProductPrices();
    }

    /**
     * Use the bundle-style range price template for event booking products.
     */
    public function getPriceHtml()
    {
        $bookingProduct = $this->getBookingProduct($this->product->id);

        if (
            $bookingProduct
            && $bookingProduct->type === 'event'
            && $bookingProduct->event_tickets->count()
        ) {
            return view('shop::products.prices.bundle', [
                'product' => $this->product,
                'prices'  => $this->getProductPrices(),
            ])->render();
        }

        return parent::getPriceHtml();
    }

    /**
     * Returns validation rules.
     *
     * @return array
     */
    public function getTypeValidationRules()
    {
        return [
            'booking.type' => [
                'required',
                function ($attribute, $value, $fail) {
                    $bookingProduct = $this->getBookingProduct($this->product->id);

                    if ($bookingProduct && $value != $bookingProduct->type) {
                        $fail(trans('admin::app.catalog.products.edit.types.booking.validations.type-mismatch'));
                    }
                },
            ],
        ];
    }
}
