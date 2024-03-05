<?php

namespace Webkul\Product\Type;

use Illuminate\Support\Str;
use Webkul\Admin\Validations\ConfigurableUniqueSku;
use Webkul\Checkout\Models\CartItem as CartItemModel;
use Webkul\Product\DataTypes\CartItemValidationResult;
use Webkul\Product\Facades\ProductImage;
use Webkul\Product\Helpers\Indexers\Price\Configurable as ConfigurableIndexer;

class Configurable extends AbstractType
{
    /**
     * Skip attribute for configurable product type.
     *
     * @var array
     */
    protected $skipAttributes = [
        'price',
        'cost',
        'special_price',
        'special_price_from',
        'special_price_to',
        'length',
        'width',
        'height',
        'weight',
        'manage_stock',
    ];

    /**
     * These are the types which can be fillable when generating variant.
     *
     * @var array
     */
    protected $fillableTypes = [
        'sku',
        'name',
        'url_key',
        'short_description',
        'description',
        'price',
        'weight',
        'status',
        'tax_category_id',
    ];

    /**
     * Is a composite product type.
     *
     * @var bool
     */
    protected $isComposite = true;

    /**
     * Show quantity box.
     *
     * @var bool
     */
    protected $showQuantityBox = true;

    /**
     * Has child products i.e. variants.
     *
     * @var bool
     */
    protected $hasVariants = true;

    /**
     * Attribute stored bu their code.
     *
     * @var bool
     */
    protected $attributesByCode = [];

    /**
     * Attribute stored bu their id.
     *
     * @var bool
     */
    protected $attributesById = [];

    /**
     * Get default variant.
     *
     * @return \Webkul\Product\Models\Product
     */
    public function getDefaultVariant()
    {
        return $this->product->variants()->find($this->getDefaultVariantId());
    }

    /**
     * Get default variant id.
     *
     * @return int
     */
    public function getDefaultVariantId()
    {
        return $this->product->additional['default_variant_id'] ?? null;
    }

    /**
     * Set default variant id.
     *
     * @param  int  $defaultVariantId
     * @return void
     */
    public function setDefaultVariantId($defaultVariantId)
    {
        $this->product->additional = array_merge($this->product->additional ?? [], [
            'default_variant_id' => $defaultVariantId,
        ]);
    }

    /**
     * Update default variant id if present in request.
     *
     * @return void
     */
    public function updateDefaultVariantId()
    {
        if (! $defaultVariantId = request()->get('default_variant_id')) {
            return;
        }

        $this->setDefaultVariantId($defaultVariantId);

        $this->product->save();
    }

    /**
     * Create configurable product.
     *
     * @return \Webkul\Product\Contracts\Product
     */
    public function create(array $data)
    {
        $product = $this->productRepository->getModel()->create($data);

        if (! isset($data['super_attributes'])) {
            return $product;
        }

        $superAttributes = [];

        foreach ($data['super_attributes'] as $attributeCode => $attributeOptions) {
            $attribute = $this->attributesByCode[$attributeCode] ?? null;

            if (empty($attribute)) {
                $attribute = $this->attributeRepository->findOneByField('code', $attributeCode);
            }

            $superAttributes[$attribute->id] = $attributeOptions;

            $product->super_attributes()->attach($attribute->id);
        }

        foreach (array_permutation($superAttributes) as $permutation) {
            $this->createVariant($product, $permutation);
        }

        return $product;
    }

    /**
     * Update configurable product.
     *
     * @param  int  $id
     * @param  string  $attribute
     * @return \Webkul\Product\Contracts\Product
     */
    public function update(array $data, $id, $attribute = 'id')
    {
        $product = parent::update($data, $id, $attribute);

        $this->updateDefaultVariantId();

        if (request()->route()?->getName() == 'admin.catalog.products.mass_update') {
            return $product;
        }

        $previousVariantIds = $product->variants->pluck('id');

        if (isset($data['variants'])) {
            foreach ($data['variants'] as $variantId => $variantData) {
                if (Str::contains($variantId, 'variant_')) {
                    $permutation = [];

                    foreach ($product->super_attributes as $superAttribute) {
                        $permutation[$superAttribute->id] = $variantData[$superAttribute->code];
                    }

                    $this->createVariant($product, $permutation, $variantData);
                } else {
                    if (is_numeric($index = $previousVariantIds->search($variantId))) {
                        $previousVariantIds->forget($index);
                    }

                    $variantData['channel'] = $data['channel'];

                    $variantData['locale'] = $data['locale'];

                    $variantData['tax_category_id'] = $data['tax_category_id'] ?? null;

                    $this->updateVariant($variantData, $variantId);
                }
            }
        }

        foreach ($previousVariantIds as $variantId) {
            $this->productRepository->delete($variantId);
        }

        return $product;
    }

    /**
     * Create variant.
     *
     * @param  \Webkul\Product\Contracts\Product  $product
     * @param  array  $permutation
     * @param  array  $data
     * @return \Webkul\Product\Contracts\Product
     */
    public function createVariant($product, $permutation, $data = [])
    {
        $data = array_merge([
            'sku'               => $sku = $product->sku.'-variant-'.implode('-', $permutation),
            'name'              => 'Variant '.implode(' ', $permutation),
            'inventories'       => [],
            'price'             => 0,
            'weight'            => 0,
            'status'            => 1,
            'tax_category_id'   => '',
            'url_key'           => $sku,
            'short_description' => $sku,
            'description'       => $sku,
        ], $data);

        $typeOfVariants = 'simple';

        $productInstance = app(config('product_types.'.$product->type.'.class'));

        if (
            isset($productInstance->variantsType)
            && ! in_array($productInstance->variantsType, ['bundle', 'configurable', 'grouped'])
        ) {
            $typeOfVariants = $productInstance->variantsType;
        }

        $variant = $this->productRepository->getModel()->create([
            'parent_id'           => $product->id,
            'type'                => $typeOfVariants,
            'attribute_family_id' => $product->attribute_family_id,
            'sku'                 => $data['sku'],
        ]);

        $attributeValues = [];

        $channels = core()->getAllChannels();

        $locales = core()->getAllLocales();

        foreach ($this->fillableTypes as $attributeCode) {
            if (! isset($data[$attributeCode])) {
                continue;
            }

            $attribute = $this->attributesByCode[$attributeCode] ?? null;

            if (empty($attribute)) {
                $attribute = $this->attributeRepository->findOneByField('code', $attributeCode);
            }

            $attributeTypeFields = $this->getAttributeTypeValues($attribute, $data[$attributeCode]);

            if ($attribute->value_per_channel) {
                if ($attribute->value_per_locale) {
                    foreach ($channels as $channel) {
                        foreach ($locales as $locale) {
                            $attributeValues[] = array_merge($attributeTypeFields, [
                                'product_id'   => $variant->id,
                                'attribute_id' => $attribute->id,
                                'channel'      => $channel->code,
                                'locale'       => $locale->code,
                            ]);
                        }
                    }
                } else {
                    foreach ($channels as $channel) {
                        $attributeValues[] = array_merge($attributeTypeFields, [
                            'product_id'   => $variant->id,
                            'attribute_id' => $attribute->id,
                            'channel'      => $channel->code,
                            'locale'       => null,
                        ]);
                    }
                }
            } else {
                if ($attribute->value_per_locale) {
                    foreach ($locales as $locale) {
                        $attributeValues[] = array_merge($attributeTypeFields, [
                            'product_id'   => $variant->id,
                            'attribute_id' => $attribute->id,
                            'channel'      => null,
                            'locale'       => $locale->code,
                        ]);
                    }
                } else {
                    $attributeValues[] = array_merge($attributeTypeFields, [
                        'product_id'   => $variant->id,
                        'attribute_id' => $attribute->id,
                        'channel'      => null,
                        'locale'       => null,
                    ]);
                }
            }
        }

        foreach ($permutation as $attributeId => $optionId) {
            $attribute = $this->attributesById[$attributeId] ?? null;

            if (empty($attribute)) {
                $attribute = $this->attributeRepository->find($attributeId);
            }

            $attributeValues[] = array_merge($this->getAttributeTypeValues($attribute, $optionId), [
                'product_id'   => $variant->id,
                'attribute_id' => $attributeId,
                'channel'      => null,
                'locale'       => null,
            ]);
        }

        $this->attributeValueRepository->insert($attributeValues);

        $this->productInventoryRepository->saveInventories($data, $variant);

        $this->productImageRepository->upload($data, $variant, 'images');

        return $variant;
    }

    /**
     * @param  mixed  $attribute
     * @param  mixed  $value
     * @return array
     */
    public function getAttributeTypeValues($attribute, $value)
    {
        $attributeTypeFields = array_fill_keys(array_values($attribute->attributeTypeFields), null);

        $attributeTypeFields[$attribute->column_name] = $value;

        return $attributeTypeFields;
    }

    /**
     * Update variant.
     *
     * @param  int  $id
     * @return \Webkul\Product\Contracts\Product
     */
    public function updateVariant(array $data, $id)
    {
        $variant = $this->productRepository->find($id);

        $variant->update(['sku' => $data['sku']]);

        foreach ($this->fillableTypes as $attributeCode) {
            if (! isset($data[$attributeCode])) {
                continue;
            }

            $attribute = $this->attributesByCode[$attributeCode] ?? null;

            if (empty($attribute)) {
                $attribute = $this->attributeRepository->findOneByField('code', $attributeCode);
            }

            if ($attribute->value_per_channel) {
                if ($attribute->value_per_locale) {
                    $productAttributeValue = $variant->attribute_values
                        ->where('channel', $attribute->value_per_channel ? $data['channel'] : null)
                        ->where('locale', $attribute->value_per_locale ? $data['locale'] : null)
                        ->where('attribute_id', $attribute->id)
                        ->first();
                } else {
                    $productAttributeValue = $variant->attribute_values
                        ->where('channel', $attribute->value_per_channel ? $data['channel'] : null)
                        ->where('attribute_id', $attribute->id)
                        ->first();
                }
            } else {
                if ($attribute->value_per_locale) {
                    $productAttributeValue = $variant->attribute_values
                        ->where('locale', $attribute->value_per_locale ? $data['locale'] : null)
                        ->where('attribute_id', $attribute->id)
                        ->first();
                } else {
                    $productAttributeValue = $variant->attribute_values
                        ->where('attribute_id', $attribute->id)
                        ->first();
                }
            }

            if (! $productAttributeValue) {
                $uniqueId = implode('|', array_filter([
                    $data['channel'],
                    $data['locale'],
                    $variant->id,
                    $attribute->id,
                ]));

                $this->attributeValueRepository->create([
                    'product_id'            => $variant->id,
                    'attribute_id'          => $attribute->id,
                    $attribute->column_name => $data[$attribute->code],
                    'channel'               => $attribute->value_per_channel ? $data['channel'] : null,
                    'locale'                => $attribute->value_per_locale ? $data['locale'] : null,
                    'unique_id'             => $uniqueId,
                ]);
            } else {
                $productAttributeValue->update([$attribute->column_name => $data[$attribute->code]]);
            }
        }

        $this->productInventoryRepository->saveInventories($data, $variant);

        return $variant;
    }

    /**
     * Copy relationships.
     *
     * @param  \Webkul\Product\Models\Product  $product
     * @return void
     */
    protected function copyRelationships($product)
    {
        parent::copyRelationships($product);

        $attributesToSkip = config('products.skipAttributesOnCopy') ?? [];

        if (
            in_array('super_attributes', $attributesToSkip)
            || in_array('variants', $attributesToSkip)
        ) {
            return;
        }

        foreach ($this->product->super_attributes as $superAttribute) {
            $product->super_attributes()->save($superAttribute);
        }

        foreach ($this->product->variants as $variant) {
            $newVariant = $variant->getTypeInstance()->copy();

            $newVariant->parent_id = $product->id;

            $newVariant->save();
        }
    }

    /**
     * Returns children ids.
     *
     * @return array
     */
    public function getChildrenIds()
    {
        return $this->product->variants()->pluck('id')->toArray();
    }

    /**
     * Is item have quantity.
     *
     * @param  \Webkul\Checkout\Contracts\CartItem  $cartItem
     * @return bool
     */
    public function isItemHaveQuantity($cartItem)
    {
        return $cartItem->child->getTypeInstance()->haveSufficientQuantity($cartItem->quantity);
    }

    /**
     * Return validation rules.
     *
     * @return array
     */
    public function getTypeValidationRules()
    {
        return [
            'variants.*.name'   => 'required',
            'variants.*.sku'    => [
                'required',
                new ConfigurableUniqueSku($this->getChildrenIds()),
            ],
            'variants.*.price'  => 'required',
            'variants.*.weight' => 'required',
        ];
    }

    /**
     * Return true if item can be moved to cart from wishlist.
     *
     * @param  \Webkul\Customer\Contracts\Wishlist  $item
     * @return bool
     */
    public function canBeMovedFromWishlistToCart($item)
    {
        return isset($item->additional['selected_configurable_option']);
    }

    /**
     * Get product prices.
     *
     * @return array
     */
    public function getProductPrices()
    {
        $minPrice = $this->evaluatePrice($this->getMinimalPrice());

        return [
            'regular' => [
                'price'           => $minPrice,
                'formatted_price' => core()->currency($minPrice),
            ],
        ];
    }

    /**
     * Get product minimal price.
     *
     * @return string
     */
    public function getPriceHtml()
    {
        return view('shop::products.prices.configurable', [
            'product' => $this->product,
            'prices'  => $this->getProductPrices(),
        ])->render();
    }

    /**
     * Add product. Returns error message if can't prepare product.
     *
     * @param  array  $data
     * @return array|string
     */
    public function prepareForCart($data)
    {
        $data['quantity'] = parent::handleQuantity((int) $data['quantity']);

        if (empty($data['selected_configurable_option'])) {
            if ($this->getDefaultVariantId()) {
                $data['selected_configurable_option'] = $this->getDefaultVariantId();
            } else {
                return trans('product::app.checkout.cart.missing-options');
            }
        }

        $data = $this->getQtyRequest($data);

        $childProduct = $this->productRepository->find($data['selected_configurable_option']);

        if (! $childProduct->haveSufficientQuantity($data['quantity'])) {
            return trans('product::app.checkout.cart.inventory-warning');
        }

        $price = $childProduct->getTypeInstance()->getFinalPrice();

        return [
            [
                'product_id'        => $this->product->id,
                'sku'               => $this->product->sku,
                'name'              => $this->product->name,
                'type'              => $this->product->type,
                'quantity'          => $data['quantity'],
                'price'             => $convertedPrice = core()->convertPrice($price),
                'base_price'        => $price,
                'total'             => $convertedPrice * $data['quantity'],
                'base_total'        => $price * $data['quantity'],
                'weight'            => $childProduct->weight,
                'total_weight'      => $childProduct->weight * $data['quantity'],
                'base_total_weight' => $childProduct->weight * $data['quantity'],
                'additional'        => $this->getAdditionalOptions($data),
            ], [
                'parent_id'  => $this->product->id,
                'product_id' => (int) $data['selected_configurable_option'],
                'sku'        => $childProduct->sku,
                'name'       => $childProduct->name,
                'type'       => $childProduct->type,
                'additional' => [
                    'product_id' => (int) $data['selected_configurable_option'],
                    'parent_id'  => $this->product->id,
                ],
            ],
        ];
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
        if ($this->product->id != $options2['product_id']) {
            return false;
        }

        if (
            isset($options1['selected_configurable_option'])
            && isset($options2['selected_configurable_option'])
        ) {
            return $options1['selected_configurable_option'] === $options2['selected_configurable_option'];
        }

        if (! isset($options1['selected_configurable_option'])) {
            return false;
        }

        if (! isset($options2['selected_configurable_option'])) {
            return false;
        }
    }

    /**
     * Return additional information for items.
     *
     * @param  array  $data
     * @return array
     */
    public function getAdditionalOptions($data)
    {
        $childProduct = app('Webkul\Product\Repositories\ProductRepository')->find($data['selected_configurable_option']);

        foreach ($this->product->super_attributes as $attribute) {
            $option = $attribute->options()->where('id', $childProduct->{$attribute->code})->first();

            $data['attributes'][$attribute->code] = [
                'attribute_name' => $attribute->name ? $attribute->name : $attribute->admin_name,
                'option_id'      => $option->id,
                'option_label'   => $option->label ? $option->label : $option->admin_name,
            ];
        }

        return $data;
    }

    /**
     * Get actual ordered item.
     *
     * @param  \Webkul\Checkout\Contracts\CartItem  $item
     * @return \Webkul\Checkout\Contracts\CartItem|\Webkul\Sales\Contracts\OrderItem|\Webkul\Sales\Contracts\InvoiceItem|\Webkul\Sales\Contracts\ShipmentItem|\Webkul\Customer\Contracts\Wishlist
     */
    public function getOrderedItem($item)
    {
        return $item->child;
    }

    /**
     * Get product base image.
     *
     * @param  \Webkul\Customer\Contracts\Wishlist|\Webkul\Checkout\Contracts\CartItem  $item
     * @return array
     */
    public function getBaseImage($item)
    {
        $product = $item->product;

        if ($item instanceof \Webkul\Customer\Contracts\Wishlist) {
            if (isset($item->additional['selected_configurable_option'])) {
                $product = $this->productRepository->find($item->additional['selected_configurable_option']);
            }
        } else {
            if (count($item->child->product->images)) {
                $product = $item->child->product;
            }
        }

        return ProductImage::getProductBaseImage($product);
    }

    /**
     * Validate cart item product price.
     *
     * @param  \Webkul\Product\Type\CartItem  $item
     */
    public function validateCartItem(CartItemModel $item): CartItemValidationResult
    {
        $result = new CartItemValidationResult();

        if ($this->isCartItemInactive($item)) {
            $result->itemIsInactive();

            return $result;
        }

        $price = $item->child->getTypeInstance()->getFinalPrice($item->quantity);

        if ($price == $item->base_price) {
            return $result;
        }

        $item->base_price = $price;
        $item->price = core()->convertPrice($price);

        $item->base_total = $price * $item->quantity;
        $item->total = core()->convertPrice($price * $item->quantity);

        $item->save();

        return $result;
    }

    /**
     * Is product have sufficient quantity.
     */
    public function haveSufficientQuantity(int $qty): bool
    {
        foreach ($this->product->variants as $variant) {
            if ($variant->haveSufficientQuantity($qty)) {
                return true;
            }
        }

        return (bool) core()->getConfigData('catalog.inventory.stock_options.back_orders');
    }

    /**
     * Return true if this product type is saleable.
     *
     * @return bool
     */
    public function isSaleable()
    {
        foreach ($this->product->variants as $variant) {
            if ($variant->isSaleable()) {
                return true;
            }
        }

        return false;
    }

    /**
     * Return total quantity.
     *
     * @return int
     */
    public function totalQuantity()
    {
        $total = 0;

        foreach ($this->product->variants as $variant) {
            $inventoryIndex = $variant->totalQuantity();

            $total += $inventoryIndex->qty;
        }

        return $total;
    }

    /**
     * Returns price indexer class for a specific product type
     *
     * @return string
     */
    public function getPriceIndexer()
    {
        return app(ConfigurableIndexer::class);
    }
}
