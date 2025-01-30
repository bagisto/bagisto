<?php

namespace Webkul\Product\Type;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use Webkul\Attribute\Repositories\AttributeRepository;
use Webkul\Checkout\Contracts\CartItem;
use Webkul\Customer\Repositories\CustomerRepository;
use Webkul\Product\DataTypes\CartItemValidationResult;
use Webkul\Product\Helpers\Indexers\Price\Virtual as VirtualIndexer;
use Webkul\Product\Repositories\ProductAttributeValueRepository;
use Webkul\Product\Repositories\ProductBundleOptionProductRepository;
use Webkul\Product\Repositories\ProductCustomerGroupPriceRepository;
use Webkul\Product\Repositories\ProductCustomizableOptionPriceRepository;
use Webkul\Product\Repositories\ProductCustomizableOptionRepository;
use Webkul\Product\Repositories\ProductGroupedProductRepository;
use Webkul\Product\Repositories\ProductImageRepository;
use Webkul\Product\Repositories\ProductInventoryRepository;
use Webkul\Product\Repositories\ProductRepository;
use Webkul\Product\Repositories\ProductVideoRepository;

class Virtual extends AbstractType
{
    /**
     * Skip attribute for virtual product type.
     *
     * @var array
     */
    protected $skipAttributes = [
        'length',
        'width',
        'height',
        'weight',
        'depth',
    ];

    /**
     * Is a stockable product type.
     *
     * @var bool
     */
    protected $isStockable = false;

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
        protected ProductGroupedProductRepository $productGroupedProductRepository,
        protected ProductBundleOptionProductRepository $productBundleOptionProductRepository,
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
     * Return true if product can be added to cart without options.
     *
     * @return bool
     */
    public function canBeAddedToCartWithoutOptions()
    {
        if ($this->product->customizable_options->isNotEmpty()) {
            return false;
        }

        return $this->canBeAddedToCartWithoutOptions;
    }

    /**
     * Is customizable.
     *
     * @return bool
     */
    public function isCustomizable()
    {
        /**
         * If the product is a child product of a configurable product, then it is not customizable.
         */
        if ($this->product->parent) {
            return false;
        }

        /**
         * If the product is a child product of a grouped product, then it is not customizable.
         */
        $associatedWithGroupedProduct = $this->productGroupedProductRepository->firstWhere('associated_product_id', $this->product->id);

        if ($associatedWithGroupedProduct) {
            return false;
        }

        /**
         * If the product is a child product of a bundle product, then it is not customizable.
         */
        $associatedWithBundleProduct = $this->productBundleOptionProductRepository->firstWhere('product_id', $this->product->id);

        if ($associatedWithBundleProduct) {
            return false;
        }

        return true;
    }

    /**
     * Return true if this product type is saleable.
     *
     * @return bool
     */
    public function isSaleable()
    {
        if (! $this->product->status) {
            return false;
        }

        if ($this->haveSufficientQuantity(1)) {
            return true;
        }

        return false;
    }

    /**
     * Have sufficient quantity.
     */
    public function haveSufficientQuantity(int $qty): bool
    {
        if (! $this->product->manage_stock) {
            return true;
        }

        return $qty <= $this->totalQuantity();
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
        return app(VirtualIndexer::class);
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
            $this->product->customizable_options->isNotEmpty()
            && empty($data['customizable_options'])
        ) {
            return trans('product::app.checkout.cart.missing-options');
        }

        $data['quantity'] = $this->handleQuantity((int) $data['quantity']);

        $data = $this->getQtyRequest($data);

        if (! $this->haveSufficientQuantity($data['quantity'])) {
            return trans('product::app.checkout.cart.inventory-warning');
        }

        $price = $this->getFinalPrice();

        if (! empty($data['customizable_options'])) {
            $formattedCustomizableOptions = $this->formatRequestedCustomizableOptions($data['customizable_options']);

            /**
             * Check if the file extension is supported.
             */
            foreach ($formattedCustomizableOptions->where('type', 'file') as $option) {
                if (
                    isset($option['prices'][0]['label'])
                    && $option['prices'][0]['label'] instanceof \Illuminate\Http\UploadedFile
                ) {
                    $extension = $option['prices'][0]['label']->getClientOriginalExtension();

                    if (
                        ! empty($option['supported_file_extensions'])
                        && ! in_array(strtolower($extension), $option['supported_file_extensions'])
                    ) {
                        return trans('product::app.checkout.cart.invalid-file-extension');
                    }
                }
            }

            /**
             * Store the files in the storage.
             */
            $formattedCustomizableOptions = $formattedCustomizableOptions->map(function ($option) use ($data) {
                if ($option['type'] === 'file') {
                    $file = $option['prices'][0]['label'];

                    if (
                        ! empty($file)
                        && $file instanceof UploadedFile
                    ) {
                        $filePath = $file->store("carts/{$data['cart_id']}");

                        $option['prices'][0]['label'] = $filePath;
                    } else {
                        $filePath = collect($data['formatted_customizable_options'] ?? [])
                            ->firstWhere('id', $option['id']);

                        $option['prices'][0]['label'] = $filePath['prices'][0]['label'] ?? '';
                    }
                }

                return $option;
            });

            $price += $formattedCustomizableOptions->sum('total_price');

            $data['formatted_customizable_options'] = $formattedCustomizableOptions->toArray();
        }

        return [
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
            $formattedCustomizableOptions = $this->formatRequestedCustomizableOptions($item->additional['customizable_options']);

            $basePrice += round($formattedCustomizableOptions->sum('total_price'), 4);
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
     * Returns validation rules.
     *
     * @return array
     */
    public function getTypeValidationRules()
    {
        return [
            'customizable_options' => [
                'array',
                function ($attribute, $value, $fail) {
                    if (! $this->isCustomizable()) {
                        $fail(trans('admin::app.catalog.products.edit.types.simple.customizable-options.validations.associated-product'));
                    }
                },
            ],
        ];
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
                        'attribute_type' => $option['type'],
                        'attribute_name' => $option['label'][app()->getLocale()] ?? $option['label'][app()->getFallbackLocale()],
                        'option_label'   => collect($option['prices'])->pluck('label')->join(', ', ' and '),
                    ];
                } else {
                    $data['attributes'][] = [
                        'attribute_type' => $option['type'],
                        'attribute_name' => $option['label'][app()->getLocale()] ?? $option['label'][app()->getFallbackLocale()],
                        'option_label'   => $option['prices'][0]['label'],
                    ];
                }
            }
        }

        return $data;
    }

    /**
     * Compare options.
     *
     * @param  array  $options1
     * @param  array  $options2
     * @return bool
     */
    public function compareOptions($options1, $options2)
    {
        if (
            isset($options1['customizable_options'])
            && isset($options2['customizable_options'])
        ) {
            return $options1['customizable_options'] == $options2['customizable_options'];
        }

        if (
            (
                ! isset($options1['customizable_options'])
                && isset($options2['customizable_options'])
            )
            || (
                isset($options1['customizable_options'])
                && ! isset($options2['customizable_options'])
            )
        ) {
            return false;
        }

        if (
            ! isset($options1['customizable_options'])
            && ! isset($options2['customizable_options'])
        ) {
            return $this->product->id == $options2['product_id'];
        }

        return false;
    }

    /**
     * Format the requested customizable options. This is a cleaned and formatted version of the customizable options
     * requested by the user.
     */
    protected function formatRequestedCustomizableOptions(array $requestedCustomizableOptions): Collection
    {
        $formattedCustomizableOptions = [];

        $customizableOptions = $this->productCustomizableOptionRepository
            ->with(['customizable_option_prices'])
            ->where('product_id', $this->product->id)
            ->whereIn('id', array_keys($requestedCustomizableOptions))
            ->get();

        foreach ($customizableOptions as $customizableOption) {
            switch ($customizableOption->type) {
                case 'text':
                case 'textarea':
                case 'date':
                case 'datetime':
                case 'time':
                    if (! $customizableOption->is_required && empty($requestedCustomizableOptions[$customizableOption->id][0])) {
                        continue 2;
                    }

                    $optionPrice = $customizableOption->customizable_option_prices->first();

                    $formattedCustomizableOptions[] = [
                        'id'          => $customizableOption->id,
                        'type'        => $customizableOption->type,
                        'label'       => $customizableOption->translations->pluck('label', 'locale')->toArray(),
                        'prices'      => [[
                            'id'    => $optionPrice->id,
                            'label' => $requestedCustomizableOptions[$customizableOption->id][0],
                            'price' => $optionPrice->price,
                        ]],
                        'total_price' => $optionPrice->price,
                    ];

                    break;

                case 'checkbox':
                case 'radio':
                case 'select':
                case 'multiselect':
                    if (! $customizableOption->is_required && empty($requestedCustomizableOptions[$customizableOption->id])) {
                        continue 2;
                    }

                    /**
                     * If the option is not required and the user has selected the `None` option, then we will skip this option.
                     */
                    if (in_array(0, $requestedCustomizableOptions[$customizableOption->id])) {
                        continue 2;
                    }

                    $optionPrices = $customizableOption->customizable_option_prices
                        ->whereIn('id', $requestedCustomizableOptions[$customizableOption->id]);

                    $formattedCustomizableOptions[] = [
                        'id'          => $customizableOption->id,
                        'type'        => $customizableOption->type,
                        'label'       => $customizableOption->translations->pluck('label', 'locale')->toArray(),
                        'prices'      => $optionPrices->map(fn ($price) => [
                            'id'    => $price->id,
                            'label' => $price->label,
                            'price' => $price->price,
                        ])->values()->toArray(),
                        'total_price' => $optionPrices->sum('price'),
                    ];

                    break;

                case 'file':
                    if (! $customizableOption->is_required && empty($requestedCustomizableOptions[$customizableOption->id][0])) {
                        continue 2;
                    }

                    $optionPrice = $customizableOption->customizable_option_prices->first();

                    /**
                     * The file object is present in the label key here. We will not store the file at this moment because we do not have
                     * the cart ID yet. The file will be stored when the cart is created. Then, we will update the label key with the
                     * file path.
                     */
                    $formattedCustomizableOptions[] = [
                        'id'                        => $customizableOption->id,
                        'type'                      => $customizableOption->type,
                        'label'                     => $customizableOption->translations->pluck('label', 'locale')->toArray(),
                        'supported_file_extensions' => collect(explode(',', $customizableOption->supported_file_extensions))
                            ->map(fn ($extension) => trim($extension))
                            ->filter(fn ($extension) => ! empty($extension))
                            ->toArray(),
                        'prices'                    => [[
                            'id'    => $optionPrice->id,
                            'label' => $requestedCustomizableOptions[$customizableOption->id][0],
                            'price' => $optionPrice->price,
                        ]],
                        'total_price' => $optionPrice->price,
                    ];

                    break;
            }
        }

        return collect($formattedCustomizableOptions);
    }
}
