<?php

namespace Webkul\Product\Type;

use Webkul\Attribute\Repositories\AttributeRepository;
use Webkul\Checkout\Contracts\CartItem;
use Webkul\Customer\Repositories\CustomerRepository;
use Webkul\Product\DataTypes\CartItemValidationResult;
use Webkul\Product\Helpers\Indexers\Price\Simple as SimpleIndexer;
use Webkul\Product\Repositories\ProductAttributeValueRepository;
use Webkul\Product\Repositories\ProductCustomerGroupPriceRepository;
use Webkul\Product\Repositories\ProductCustomizableOptionPriceRepository;
use Webkul\Product\Repositories\ProductCustomizableOptionRepository;
use Webkul\Product\Repositories\ProductImageRepository;
use Webkul\Product\Repositories\ProductInventoryRepository;
use Webkul\Product\Repositories\ProductRepository;
use Webkul\Product\Repositories\ProductVideoRepository;

class Simple extends AbstractType
{
    /**
     * Show quantity box.
     *
     * @var bool
     */
    protected $showQuantityBox = true;

    /**
     * Create a new product type instance.
     *
     * @return void
     */
    public function __construct(
        CustomerRepository $customerRepository,
        AttributeRepository $attributeRepository,
        ProductRepository $productRepository,
        ProductAttributeValueRepository $attributeValueRepository,
        ProductInventoryRepository $productInventoryRepository,
        ProductImageRepository $productImageRepository,
        ProductVideoRepository $productVideoRepository,
        ProductCustomerGroupPriceRepository $productCustomerGroupPriceRepository,
        protected ProductCustomizableOptionRepository $productCustomizableOptionRepository,
        protected ProductCustomizableOptionPriceRepository $productCustomizableOptionPriceRepository,
    ) {
        parent::__construct(
            $customerRepository,
            $attributeRepository,
            $productRepository,
            $attributeValueRepository,
            $productInventoryRepository,
            $productImageRepository,
            $productVideoRepository,
            $productCustomerGroupPriceRepository
        );
    }

    /**
     * Update.
     *
     * @param  int  $id
     * @param  array  $attributes
     * @return \Webkul\Product\Contracts\Product
     */
    public function update(array $data, $id, $attributes = [])
    {
        $product = parent::update($data, $id, $attributes);

        if (! empty($attributes)) {
            return $product;
        }

        $this->productCustomizableOptionRepository->saveCustomizableOptions($data, $product);

        return $product;
    }

    /**
     * Return true if this product type is saleable. Saleable check added because
     * this is the point where all parent product will recall this.
     *
     * @return bool
     */
    public function isSaleable()
    {
        if (! $this->product->status) {
            return false;
        }

        return $this->haveSufficientQuantity(1);
    }

    /**
     * Have sufficient quantity.
     */
    public function haveSufficientQuantity(int $qty): bool
    {
        if (! $this->product->manage_stock) {
            return true;
        }

        return $qty <= $this->totalQuantity() ?: (bool) core()->getConfigData('catalog.inventory.stock_options.back_orders');
    }

    /**
     * Get product maximum price.
     *
     * @return float
     */
    public function getMaximumPrice()
    {
        return $this->product->price;
    }

    /**
     * Returns price indexer class for a specific product type
     *
     * @return string
     */
    public function getPriceIndexer()
    {
        return app(SimpleIndexer::class);
    }

    /**
     * Add product. Returns error message if can't prepare product.
     *
     * @param  array  $data
     * @return array
     */
    public function prepareForCart($data)
    {
        $data['quantity'] = $this->handleQuantity((int) $data['quantity']);

        $data = $this->getQtyRequest($data);

        if (! $this->haveSufficientQuantity($data['quantity'])) {
            return trans('product::app.checkout.cart.inventory-warning');
        }

        $price = $this->getFinalPrice();

        if (! empty($data['customizable_options'])) {
            $data['formatted_customizable_options'] = $this->getFormattedCustomizableOptions($data['customizable_options']);

            $price += collect($data['formatted_customizable_options'])->sum('total_price');
        }

        $products = [
            [
                'product_id'          => $this->product->id,
                'sku'                 => $this->product->sku,
                'quantity'            => $data['quantity'],
                'name'                => $this->product->name,
                'price'               => $convertedPrice = core()->convertPrice($price),
                'price_incl_tax'      => $convertedPrice,
                'base_price'          => $price,
                'base_price_incl_tax' => $price,
                'total'               => $convertedPrice * $data['quantity'],
                'total_incl_tax'      => $convertedPrice * $data['quantity'],
                'base_total'          => $price * $data['quantity'],
                'base_total_incl_tax' => $price * $data['quantity'],
                'weight'              => (float) ($this->product->weight ?? 0),
                'total_weight'        => (float) ($this->product->weight ?? 0) * $data['quantity'],
                'base_total_weight'   => (float) ($this->product->weight ?? 0) * $data['quantity'],
                'type'                => $this->product->type,
                'additional'          => $this->getAdditionalOptions($data),
            ],
        ];

        return $products;
    }

    /**
     * Validate cart item product price and other things.
     */
    public function validateCartItem(CartItem $item): CartItemValidationResult
    {
        $validation = new CartItemValidationResult;

        if ($this->isCartItemInactive($item)) {
            $validation->itemIsInactive();

            return $validation;
        }

        $basePrice = round($this->getFinalPrice($item->quantity), 4);

        /**
         * Here, we will not check for the formatted customizable option key directly. Instead, we will use the original request keys
         * and retrieve the formatted options from the database again, similar to how we handled the base price above.
         */
        if (! empty($item->additional['customizable_options'])) {
            $formattedCustomizableOptions = $this->getFormattedCustomizableOptions($item->additional['customizable_options']);

            $basePrice += round(collect($formattedCustomizableOptions)->sum('total_price'), 4);
        }

        if ($basePrice == $item->base_price_incl_tax) {
            return $validation;
        }

        $item->base_price = $basePrice;
        $item->base_price_incl_tax = $basePrice;

        $item->price = ($price = core()->convertPrice($basePrice));
        $item->price_incl_tax = $price;

        $item->base_total = $basePrice * $item->quantity;
        $item->base_total_incl_tax = $basePrice * $item->quantity;

        $item->total = ($total = core()->convertPrice($basePrice * $item->quantity));
        $item->total_incl_tax = $total;

        $item->save();

        return $validation;
    }

    /**
     * Returns additional information for items.
     *
     * @param  array  $data
     * @return array
     */
    public function getAdditionalOptions($data)
    {
        if (! empty($data['formatted_customizable_options'])) {
            $data['attributes'] = [];

            foreach ($data['formatted_customizable_options'] as $option) {
                if (in_array($option['type'], ['checkbox', 'multiselect'])) {
                    $data['attributes'][] = [
                        'attribute_name' => $option['label'][app()->getLocale()],
                        'option_label'   => collect($option['prices'])->pluck('label')->join(', ', ' and '),
                    ];
                } else {
                    $data['attributes'][] = [
                        'attribute_name' => $option['label'][app()->getLocale()],
                        'option_label'   => $option['prices'][0]['label'],
                    ];
                }
            }
        }

        return $data;
    }

    /**
     * Get formatted customizable options. This is a cleaned and formatted version of the customizable options requested by the user.
     * It will be used to display the options on necessary pages such as the cart, order, invoice, shipment, etc.
     */
    protected function getFormattedCustomizableOptions(array $requestedCustomizableOptions): array
    {
        $formattedCustomizableOptions = [];

        $customizableOptions = $this->productCustomizableOptionRepository
            ->with(['customizable_option_prices'])
            ->where('product_id', $this->product->id)
            ->whereIn('id', array_keys($requestedCustomizableOptions))
            ->get();

        foreach ($customizableOptions as $customizableOption) {
            if (! $customizableOption->is_required && empty($requestedCustomizableOptions[$customizableOption->id])) {
                continue;
            }

            switch ($customizableOption->type) {
                case 'text':
                case 'textarea':
                case 'date':
                case 'datetime':
                case 'time':
                    $optionPrice = $customizableOption->customizable_option_prices->first();

                    $formattedCustomizableOptions[] = [
                        'type'        => $customizableOption->type,
                        'label'       => $customizableOption->translations->pluck('label', 'locale')->toArray(),
                        'prices'      => [['label' => $requestedCustomizableOptions[$customizableOption->id][0], 'price' => $optionPrice->price]],
                        'total_price' => $optionPrice->price,
                    ];

                    break;

                case 'checkbox':
                case 'radio':
                case 'select':
                case 'multiselect':
                    $optionPrices = $customizableOption->customizable_option_prices
                        ->whereIn('id', $requestedCustomizableOptions[$customizableOption->id]);

                    $formattedCustomizableOptions[] = [
                        'type'        => $customizableOption->type,
                        'label'       => $customizableOption->translations->pluck('label', 'locale')->toArray(),
                        'prices'      => $optionPrices->map(fn ($price) => ['label' => $price->label, 'price' => $price->price])->toArray(),
                        'total_price' => $optionPrices->sum('price'),
                    ];

                    break;

                case 'file':
                    $optionPrice = $customizableOption->customizable_option_prices->first();

                    $formattedCustomizableOptions[] = [
                        'type'        => $customizableOption->type,
                        'label'       => $customizableOption->translations->pluck('label', 'locale')->toArray(),
                        'prices'      => [['label' => 'file-link', 'price' => $optionPrice->price]],
                        'total_price' => $optionPrice->price,
                    ];

                    break;
            }
        }

        return $formattedCustomizableOptions;
    }
}
