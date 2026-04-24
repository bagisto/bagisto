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
     * Booking products require slot/ticket/date options before they can be
     * added to the cart.
     *
     * @var bool
     */
    protected $canBeAddedToCartWithoutOptions = false;

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
        return true;
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
     * Return true if the booking product has bookable inventory for reorder / saleability checks.
     */
    public function isSaleable()
    {
        if (! $this->product->status) {
            return false;
        }

        $bookingProduct = $this->getBookingProduct($this->product->id);

        if (! $bookingProduct) {
            return false;
        }

        if (
            $bookingProduct->available_to
            && Carbon::now() > $bookingProduct->available_to
        ) {
            return false;
        }

        if ($bookingProduct->type === 'event') {
            foreach ($bookingProduct->event_tickets as $ticket) {
                if ((int) $ticket->qty > 0) {
                    return true;
                }
            }

            return false;
        }

        if ($bookingProduct->type === 'appointment') {
            return true;
        }

        return (int) $bookingProduct->qty > 0;
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
            if (isset($data['booking']['slot']['from'], $data['booking']['slot']['to'])) {
                $duration = (int) $data['booking']['slot']['to'] - (int) $data['booking']['slot']['from'];

                if ($duration < 3600) {
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
                            'qty' => $available,
                        ])
                        : trans('shop::app.products.booking.cart.integrity.event.ticket_sold_out', [
                            'ticket' => $ticketName,
                        ]);

                    throw new InsufficientProductInventoryException($message);
                }
            }

            $messageKey = match ($bookingProduct->type) {
                'rental' => 'shop::app.products.booking.cart.integrity.rental_unavailable',
                default => 'shop::app.products.booking.cart.integrity.inventory_warning',
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

        if (! isset($options1['booking'], $options2['booking'])) {
            return false;
        }

        $booking1 = $options1['booking'];
        $booking2 = $options2['booking'];

        if (isset($booking1['ticket_id'], $booking2['ticket_id'])) {
            return $booking1['ticket_id'] === $booking2['ticket_id'];
        }

        if (isset($booking1['date_from'], $booking2['date_from'], $booking1['date_to'], $booking2['date_to'])) {
            return $booking1['date_from'] === $booking2['date_from']
                && $booking1['date_to'] === $booking2['date_to']
                && ($booking1['renting_type'] ?? null) === ($booking2['renting_type'] ?? null);
        }

        if (
            isset($booking1['slot']['from'], $booking2['slot']['from'])
            && isset($booking1['slot']['to'], $booking2['slot']['to'])
        ) {
            return (string) $booking1['slot']['from'] === (string) $booking2['slot']['from']
                && (string) $booking1['slot']['to'] === (string) $booking2['slot']['to'];
        }

        if (isset($booking1['date'], $booking2['date']) && isset($booking1['slot'], $booking2['slot'])) {
            return $booking1['date'] === $booking2['date']
                && (string) $booking1['slot'] === (string) $booking2['slot'];
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
     * Render the PDP/card price as "Starting from {base + cheapest extra}" for
     * booking sub-types whose final price is composed at add-to-cart time:
     *  - event  → base + cheapest ticket price
     *  - rental → base + minimum unit rate (1 hour if hourly is offered, otherwise 1 day)
     * Other sub-types fall through to the default.
     */
    public function getPriceHtml()
    {
        $bookingProduct = $this->getBookingProduct($this->product->id);

        if (! $bookingProduct) {
            return parent::getPriceHtml();
        }

        $cheapestExtra = $this->getCheapestBookingExtra($bookingProduct);

        if ($cheapestExtra === null) {
            return parent::getPriceHtml();
        }

        $fromPrice = (float) parent::getMinimalPrice() + (float) $cheapestExtra;

        $labelKey = match ($bookingProduct->type) {
            'event' => 'shop::app.products.view.type.booking.event.starting-from',
            'rental' => 'shop::app.products.view.type.booking.rental.starting-from',
            default => null,
        };

        if (! $labelKey) {
            return parent::getPriceHtml();
        }

        return view('shop::products.prices.booking-starting-from', [
            'label' => trans($labelKey),
            'prices' => [
                'regular' => [
                    'price' => core()->convertPrice($fromPrice),
                    'formatted_price' => core()->currency($fromPrice),
                ],
            ],
        ])->render();
    }

    /**
     * Return the smallest additional amount that will be charged on top of the
     * product's base price for the given booking product, or null if no such
     * minimum can be computed (unsupported sub-type, missing slot, etc.).
     */
    protected function getCheapestBookingExtra($bookingProduct): ?float
    {
        if ($bookingProduct->type === 'event') {
            if (! $bookingProduct->event_tickets->count()) {
                return null;
            }

            $helper = app($this->bookingHelper->getTypeHelper('event'));

            $cheapest = null;

            foreach ($bookingProduct->event_tickets as $ticket) {
                $ticketPrice = $helper->isInSale($ticket)
                    ? (float) $ticket->special_price
                    : (float) $ticket->price;

                if ($cheapest === null || $ticketPrice < $cheapest) {
                    $cheapest = $ticketPrice;
                }
            }

            return $cheapest;
        }

        if ($bookingProduct->type === 'rental') {
            $slot = $bookingProduct->rental_slot;

            if (! $slot) {
                return null;
            }

            $rates = array_filter([
                (float) $slot->hourly_price,
                (float) $slot->daily_price,
            ], fn ($rate) => $rate > 0);

            return $rates ? min($rates) : null;
        }

        return null;
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
