<?php

namespace Webkul\Product\Type;

use Illuminate\Support\Str;
use Webkul\Admin\Validations\ConfigurableUniqueSku;
use Webkul\Checkout\Models\CartItem as CartItemModel;
use Webkul\Product\DataTypes\CartItemValidationResult;
use Webkul\Product\Facades\ProductImage;
use Webkul\Product\Helpers\Indexers\Price\Configurable as ConfigurableIndexer;
use Webkul\Tax\Facades\Tax;

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
    protected $fillableVariantAttributeCodes = [
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
     * These are the types which can be fillable when generating variant.
     *
     * @var array
     */
    protected $fillableVariantAttributes = [];

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
     * Product can be added to cart with options or not.
     *
     * @var bool
     */
    protected $canBeAddedToCartWithoutOptions = false;

    /**
     * Has child products i.e. variants.
     *
     * @var bool
     */
    protected $hasVariants = true;

    /**
     * Create configurable product.
     *
     * @return \Webkul\Product\Contracts\Product
     */
    public function create(array $data)
    {
        $product = parent::create($data);

        if (! isset($data['super_attributes'])) {
            return $product;
        }

        /**
         * Load fillable variant attributes.
         */
        $this->fillableVariantAttributes = $this->attributeRepository->findWhereIn('code', $this->fillableVariantAttributeCodes);

        $superAttributes = [];

        foreach ($data['super_attributes'] as $attributeCode => $attributeOptions) {
            $attribute = $this->getAttributeByCode($attributeCode);

            $this->fillableVariantAttributes->push($attribute);

            $superAttributes[$attribute->code] = $attributeOptions;

            $product->super_attributes()->attach($attribute->id);
        }

        foreach (array_permutation($superAttributes) as $permutation) {
            $this->createVariant($product, $permutation, [
                'channel' => $data['channel'] ?? core()->getDefaultChannelCode(),
                'locale'  => $data['locale'] ?? core()->getDefaultLocaleCodeFromDefaultChannel(),
            ]);
        }

        return $product;
    }

    /**
     * Update configurable product.
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

        /**
         * Load fillable variant attributes.
         */
        $this->fillableVariantAttributes = $this->attributeRepository->findWhereIn('code', $this->fillableVariantAttributeCodes);

        $previousVariantIds = $product->variants->pluck('id');

        foreach ($data['variants'] ?? [] as $variantId => $variantData) {
            if (Str::contains($variantId, 'variant_')) {
                $permutation = [];

                foreach ($product->super_attributes as $superAttribute) {
                    $permutation[$superAttribute->id] = $variantData[$superAttribute->code];
                }

                $this->createVariant($product, $permutation, array_merge($variantData, [
                    'channel' => $data['channel'] ?? core()->getDefaultChannelCode(),
                    'locale'  => $data['locale'] ?? core()->getDefaultLocaleCodeFromDefaultChannel(),
                ]));
            } else {
                if (is_numeric($index = $previousVariantIds->search($variantId))) {
                    $previousVariantIds->forget($index);
                }

                $this->updateVariant(array_merge($variantData, [
                    'channel'         => $data['channel'] ?? core()->getDefaultChannelCode(),
                    'locale'          => $data['locale'] ?? core()->getDefaultLocaleCodeFromDefaultChannel(),
                    'tax_category_id' => $data['tax_category_id'] ?? null,
                ]), $variantId);
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
     * @param  array  $superAttributes
     * @param  array  $data
     * @return \Webkul\Product\Contracts\Product
     */
    public function createVariant($product, $superAttributes, $data = [])
    {
        $sku = $product->sku.'-variant-'.implode('-', $superAttributes);

        $data = array_merge([
            'sku'               => $sku,
            'name'              => 'Variant '.implode(' ', $superAttributes),
            'price'             => 0,
            'weight'            => 0,
            'status'            => 1,
            'tax_category_id'   => '',
            'url_key'           => $sku,
            'short_description' => $sku,
            'description'       => $sku,
            'inventories'       => [],
        ], $data);

        $variant = parent::create([
            'type'                => 'simple',
            'sku'                 => $sku,
            'attribute_family_id' => $product->attribute_family_id,
            'parent_id'           => $product->id,
        ]);

        foreach ($superAttributes as $attributeCode => $optionId) {
            $data[$attributeCode] = $optionId;
        }

        $this->attributeValueRepository->saveValues($data, $variant, $this->fillableVariantAttributes);

        $this->productInventoryRepository->saveInventories($data, $variant);

        $this->productImageRepository->upload($data, $variant, 'images');

        return $variant;
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

        $this->attributeValueRepository->saveValues($data, $variant, $this->fillableVariantAttributes);

        $this->productInventoryRepository->saveInventories($data, $variant);

        $this->productImageRepository->upload($data, $variant, 'images');

        $variant->channels()->sync($variant->parent->channels->pluck('id')->toArray());

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
        $minPrice = $this->getMinimalPrice();

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
            return trans('product::app.checkout.cart.missing-options');
        }

        $data = $this->getQtyRequest($data);

        $childProduct = $this->productRepository->find($data['selected_configurable_option']);

        if (! $childProduct->haveSufficientQuantity($data['quantity'])) {
            return trans('product::app.checkout.cart.inventory-warning');
        }

        $price = $childProduct->getTypeInstance()->getFinalPrice();

        return [
            [
                'product_id'          => $this->product->id,
                'sku'                 => $this->product->sku,
                'name'                => $this->product->name,
                'type'                => $this->product->type,
                'quantity'            => $data['quantity'],
                'price'               => $convertedPrice = core()->convertPrice($price),
                'price_incl_tax'      => $convertedPrice,
                'base_price'          => $price,
                'base_price_incl_tax' => $price,
                'total'               => $convertedPrice * $data['quantity'],
                'total_incl_tax'      => $convertedPrice * $data['quantity'],
                'base_total'          => $price * $data['quantity'],
                'base_total_incl_tax' => $price * $data['quantity'],
                'weight'              => $childProduct->weight,
                'total_weight'        => $childProduct->weight * $data['quantity'],
                'base_total_weight'   => $childProduct->weight * $data['quantity'],
                'additional'          => $this->getAdditionalOptions($data),
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
        $validation = new CartItemValidationResult;

        if ($this->isCartItemInactive($item)) {
            $validation->itemIsInactive();

            return $validation;
        }

        $basePrice = $item->child->getTypeInstance()->getFinalPrice($item->quantity);

        if (Tax::isInclusiveTaxProductPrices()) {
            $itemBasePrice = $item->base_price_incl_tax;
        } else {
            $itemBasePrice = $item->base_price;
        }

        if ($basePrice == $itemBasePrice) {
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
