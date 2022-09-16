<?php

namespace Webkul\Product\Type;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Webkul\Checkout\Models\CartItem as CartItemModel;
use Webkul\Product\Datatypes\CartItemValidationResult;
use Webkul\Product\Facades\ProductImage;
use Webkul\Product\Models\ProductAttributeValue;
use Webkul\Product\Models\ProductFlat;

class Configurable extends AbstractType
{
    /**
     * Skip attribute for configurable product type.
     *
     * @var array
     */
    protected $skipAttributes = ['price', 'cost', 'special_price', 'special_price_from', 'special_price_to', 'length', 'width', 'height', 'weight'];

    /**
     * These are the types which can be fillable when generating variant.
     *
     * @var array
     */
    protected $fillableTypes = ['sku', 'name', 'url_key', 'short_description', 'description', 'price', 'weight', 'status'];

    /**
     * These blade files will be included in product edit page.
     *
     * @var array
     */
    protected $additionalViews = [
        'admin::catalog.products.accordians.images',
        'admin::catalog.products.accordians.videos',
        'admin::catalog.products.accordians.categories',
        'admin::catalog.products.accordians.variations',
        'admin::catalog.products.accordians.channels',
        'admin::catalog.products.accordians.product-links',
    ];

    /**
     * Is a composite product type.
     *
     * @var boolean
     */
    protected $isComposite = true;

    /**
     * Show quantity box.
     *
     * @var boolean
     */
    protected $showQuantityBox = true;

    /**
     * Has child products i.e. variants.
     *
     * @var boolean
     */
    protected $hasVariants = true;

    /**
     * Product options.
     */
    protected $productOptions = [];

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
        $defaultVariantId = request()->get('default_variant_id');

        if ($defaultVariantId) {
            $this->setDefaultVariantId($defaultVariantId);

            $this->product->save();
        }
    }

    /**
     * Create configurable product.
     *
     * @param  array  $data
     * @return \Webkul\Product\Contracts\Product
     */
    public function create(array $data)
    {
        $product = $this->productRepository->getModel()->create($data);

        if (isset($data['super_attributes'])) {
            $super_attributes = [];

            foreach ($data['super_attributes'] as $attributeCode => $attributeOptions) {
                $attribute = $this->getAttributeByCode($attributeCode);

                $super_attributes[$attribute->id] = $attributeOptions;

                $product->super_attributes()->attach($attribute->id);
            }

            foreach (array_permutation($super_attributes) as $permutation) {
                $this->createVariant($product, $permutation);
            }
        }

        return $product;
    }

    /**
     * Update configurable product.
     *
     * @param  array   $data
     * @param  int     $id
     * @param  string  $attribute
     * @return \Webkul\Product\Contracts\Product
     */
    public function update(array $data, $id, $attribute = 'id')
    {
        $product = parent::update($data, $id, $attribute);

        $this->updateDefaultVariantId();

        $route = request()->route() ? request()->route()->getName() : '';

        if ($route != 'admin.catalog.products.massupdate') {
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

                        $this->updateVariant($variantData, $variantId);
                    }
                }
            }

            foreach ($previousVariantIds as $variantId) {
                $this->productRepository->delete($variantId);
            }
        }

        return $product;
    }

    /**
     * Create variant.
     *
     * @param  \Webkul\Product\Contracts\Product  $product
     * @param  array                              $permutation
     * @param  array                              $data
     * @return \Webkul\Product\Contracts\Product
     */
    public function createVariant($product, $permutation, $data = [])
    {
        if (! count($data)) {
            $data = [
                'sku'         => $product->sku . '-variant-' . implode('-', $permutation),
                'name'        => '',
                'inventories' => [],
                'price'       => 0,
                'weight'      => 0,
                'status'      => 1,
            ];
        }

        $data = $this->fillRequiredFields($data);

        $typeOfVariants = 'simple';
        $productInstance = app(config('product_types.' . $product->type . '.class'));

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

        foreach ($this->fillableTypes as $attributeCode) {
            if (! isset($data[$attributeCode])) {
                continue;
            }

            $attribute = $this->getAttributeByCode($attributeCode);

            $attributeTypeFields = $this->getAttributeTypeValues($attribute->type, $data[$attributeCode]);

            if ($attribute->value_per_channel) {
                if ($attribute->value_per_locale) {
                    foreach (core()->getAllChannels() as $channel) {
                        foreach (core()->getAllLocales() as $locale) {
                            $attributeValues[] = array_merge($attributeTypeFields, [
                                'product_id'   => $variant->id,
                                'attribute_id' => $attribute->id,
                                'channel'      => $channel->code,
                                'locale'       => $locale->code,
                            ]);
                        }
                    }
                } else {
                    foreach (core()->getAllChannels() as $channel) {
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
                    foreach (core()->getAllLocales() as $locale) {
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
            $attribute = $this->getAttributeById($attributeId);

            $attributeValues[] = array_merge($this->getAttributeTypeValues($attribute->type, $optionId), [
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
     * @param  string  $attributeType
     * @param  mixed  $value
     * @return array
     */
    public function getAttributeTypeValues($attributeType, $value)
    {
        $attributeTypeFields = array_fill_keys(array_values(ProductAttributeValue::$attributeTypeFields), null);

        $attributeTypeFields[ProductAttributeValue::$attributeTypeFields[$attributeType]] = $value;

        return $attributeTypeFields;
    }

    /**
     * Update variant.
     *
     * @param  array  $data
     * @param  int    $id
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

            $attribute = $this->getAttributeByCode($attributeCode);

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

            $columnName = ProductAttributeValue::$attributeTypeFields[$attribute->type];

            if (! $productAttributeValue) {
                $this->attributeValueRepository->create([
                    'product_id'   => $variant->id,
                    'attribute_id' => $attribute->id,
                    $columnName    => $data[$attribute->code],
                    'channel'      => $attribute->value_per_channel ? $data['channel'] : null,
                    'locale'       => $attribute->value_per_locale ? $data['locale'] : null,
                ]);
            } else {
                $productAttributeValue->update([$columnName => $data[$attribute->code]]);
            }
        }

        $this->productInventoryRepository->saveInventories($data, $variant);

        return $variant;
    }

    /**
     * Fill required fields.
     *
     * @param  array  $data
     * @param  int  $id
     * @return \Webkul\Product\Contracts\Product
     */
    public function fillRequiredFields(array $data): array
    {
        /**
         * Name field is not present when variant is created so adding sku.
         */
        return array_merge($data, [
            'url_key'           => $data['sku'],
            'short_description' => $data['sku'],
            'description'       => $data['sku'],
        ]);
    }

    /**
     * Check variant option availability.
     *
     * @param  array  $data
     * @param  \Webkul\Product\Contracts\Product  $product
     * @return bool
     */
    public function checkVariantOptionAvailabiliy($data, $product)
    {
        $superAttributeCodes = $product->parent->super_attributes->pluck('code');

        foreach ($product->parent->variants as $variant) {
            if ($variant->id == $product->id) {
                continue;
            }

            $matchCount = 0;

            foreach ($superAttributeCodes as $attributeCode) {
                if (! isset($data[$attributeCode])) {
                    return false;
                }

                if ($data[$attributeCode] == $variant->{$attributeCode}) {
                    $matchCount++;
                }
            }

            if ($matchCount == $superAttributeCodes->count()) {
                return true;
            }
        }

        return false;
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
        return $cartItem->child->product->getTypeInstance()->haveSufficientQuantity($cartItem->quantity);
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
            'variants.*.sku'    => 'required',
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
        if (isset($item->additional['selected_configurable_option'])) {
            return true;
        }

        return false;
    }

    /**
     * Get product minimal price.
     *
     * @param  int  $qty
     * @return float
     */
    public function getMinimalPrice($qty = null)
    {
        static $minPrice = null;

        if (! is_null($minPrice)) {
            return $minPrice;
        }

        /* method is calling many time so using variable */
        $tablePrefix = DB::getTablePrefix();

        $result = ProductFlat::join('products', 'product_flat.product_id', '=', 'products.id')
            ->distinct()
            ->where('products.parent_id', $this->product->id)
            ->selectRaw("IF( {$tablePrefix}product_flat.special_price_from IS NOT NULL
            AND {$tablePrefix}product_flat.special_price_to IS NOT NULL , IF( NOW( ) >= {$tablePrefix}product_flat.special_price_from
            AND NOW( ) <= {$tablePrefix}product_flat.special_price_to, IF( {$tablePrefix}product_flat.special_price IS NULL OR {$tablePrefix}product_flat.special_price = 0 , {$tablePrefix}product_flat.price, LEAST( {$tablePrefix}product_flat.special_price, {$tablePrefix}product_flat.price ) ) , {$tablePrefix}product_flat.price ) , IF( {$tablePrefix}product_flat.special_price_from IS NULL , IF( {$tablePrefix}product_flat.special_price_to IS NULL , IF( {$tablePrefix}product_flat.special_price IS NULL OR {$tablePrefix}product_flat.special_price = 0 , {$tablePrefix}product_flat.price, LEAST( {$tablePrefix}product_flat.special_price, {$tablePrefix}product_flat.price ) ) , IF( NOW( ) <= {$tablePrefix}product_flat.special_price_to, IF( {$tablePrefix}product_flat.special_price IS NULL OR {$tablePrefix}product_flat.special_price = 0 , {$tablePrefix}product_flat.price, LEAST( {$tablePrefix}product_flat.special_price, {$tablePrefix}product_flat.price ) ) , {$tablePrefix}product_flat.price ) ) , IF( {$tablePrefix}product_flat.special_price_to IS NULL , IF( NOW( ) >= {$tablePrefix}product_flat.special_price_from, IF( {$tablePrefix}product_flat.special_price IS NULL OR {$tablePrefix}product_flat.special_price = 0 , {$tablePrefix}product_flat.price, LEAST( {$tablePrefix}product_flat.special_price, {$tablePrefix}product_flat.price ) ) , {$tablePrefix}product_flat.price ) , {$tablePrefix}product_flat.price ) ) ) AS min_price")
            ->where('product_flat.channel', core()->getCurrentChannelCode())
            ->get();


        $minPrices = [];

        foreach ($result as $price) {
            $minPrices[] = $price->min_price;
        }

        if (empty($minPrices)) {
            return 0;
        }

        return $minPrice = min($minPrices);
    }

    /**
     * Get product offer price.
     *
     * @return float
     */
    public function getOfferPrice()
    {
        static $offerPrice = null;

        if (! is_null($offerPrice)) {
            return $offerPrice;
        }

        $rulePrices = $customerGroupPrices = [];

        foreach ($this->product->variants as $variant) {
            $rulePrice = app('Webkul\CatalogRule\Helpers\CatalogRuleProductPrice')->getRulePrice($variant);

            if ($rulePrice) {
                $rulePrices[] = $rulePrice->price;
            }

            $customerGroupPrices[] = $this->getCustomerGroupPrice($variant, 1);
        }

        if (
            $rulePrices
            || $customerGroupPrices
        ) {
            return $offerPrice = min(array_merge($rulePrices, $customerGroupPrices));
        }

        return $offerPrice = [];
    }

    /**
     * Check for offer.
     *
     * @return bool
     */
    public function haveOffer()
    {
        if ($this->getOfferPrice() < $this->getMinimalPrice()) {
            return true;
        }

        return false;
    }

    /**
     * Get product maximum price.
     *
     * @return float
     */
    public function getMaximumPrice()
    {
        static $maxPrice = null;

        if (! is_null($maxPrice)) {
            return $maxPrice;
        }

        $productFlat = ProductFlat::join('products', 'product_flat.product_id', '=', 'products.id')
            ->distinct()
            ->where('products.parent_id', $this->product->id)
            ->selectRaw('MAX(' . DB::getTablePrefix() . 'product_flat.price) AS max_price')
            ->where('product_flat.channel', core()->getCurrentChannelCode())
            ->where('product_flat.locale', app()->getLocale())
            ->first();

        return $maxPrice = $productFlat ? $productFlat->max_price : 0;
    }

    /**
     * Get product prices.
     *
     * @return array
     */
    public function getProductPrices()
    {
        $haveOffer = $this->haveOffer();

        return [
            'regular_price' => [
                'formated_price' => $haveOffer
                    ? core()->currency($this->evaluatePrice($offerPrice = $this->getOfferPrice()))
                    : core()->currency($this->evaluatePrice($minimalPrice = $this->getMinimalPrice())),
                'price'          => $haveOffer
                    ? $this->evaluatePrice($offerPrice)
                    : $this->evaluatePrice($minimalPrice),
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
        if ($this->haveSpecialPrice()) {
            return '<div class="sticker sale">' . trans('shop::app.products.sale') . '</div>'
            . '<span class="price-label">' . trans('shop::app.products.price-label') . '</span>'
            . '<span class="special-price">' . core()->currency($this->evaluatePrice($this->getMinimalPrice())) . '</span>'.'<span class="regular-price"></span>';
        } elseif ($this->haveOffer()) {
            return '<div class="sticker sale">' . trans('shop::app.products.sale') . '</div>'
            . '<span class="price-label">' . trans('shop::app.products.price-label') . '</span>'
            . '<span class="regular-price">' . core()->currency($this->evaluatePrice($this->getMinimalPrice())) . '</span>'
            . '<span class="final-price">' . core()->currency($this->evaluatePrice($this->getOfferPrice())) . '</span>';
        } else {
            return '<span class="price-label">' . trans('shop::app.products.price-label') . '</span>'
            . ' '
            . '<span class="special-price">' . core()->currency($this->evaluatePrice($this->getMinimalPrice())) . '</span> <span class="regular-price"></span>';
        }
    }

    /**
     * Check whether configurable product have special price.
     *
     * @param  int  $qty
     * @return bool
     */
    public function haveSpecialPrice($qty = null)
    {
        $haveSpecialPrice = false;

        foreach ($this->product->variants as $variant) {
            if ($variant->getTypeInstance()->haveSpecialPrice()) {
                $haveSpecialPrice = true;

                break;
            }
        }

        return $haveSpecialPrice;
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
        
        if (
            ! isset($data['selected_configurable_option'])
            || ! $data['selected_configurable_option']
        ) {
            if ($this->getDefaultVariantId()) {
                $data['selected_configurable_option'] = $this->getDefaultVariantId();
            } else {
                return trans('shop::app.checkout.cart.integrity.missing_options');
            }
        }

        $data = $this->getQtyRequest($data);

        $childProduct = $this->productRepository->find($data['selected_configurable_option']);

        if (! $childProduct->haveSufficientQuantity($data['quantity'])) {
            return trans('shop::app.checkout.cart.quantity.inventory_warning');
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
        if ($item instanceof \Webkul\Customer\Contracts\Wishlist) {
            if (isset($item->additional['selected_configurable_option'])) {
                $product = $this->productRepository->find($item->additional['selected_configurable_option']);
            } else {
                $product = $item->product;
            }
        } else {
            if ($item instanceof \Webkul\Checkout\Contracts\CartItem) {
                $product = $item->child->product;
            } else {
                if (count($item->child->product->images)) {
                    $product = $item->child->product;
                } else {
                    $product = $item->product;
                }
            }
        }

        return ProductImage::getProductBaseImage($product);
    }

    /**
     * Validate cart item product price.
     *
     * @param  \Webkul\Product\Type\CartItem  $item
     * @return \Webkul\Product\Datatypes\CartItemValidationResult
     */
    public function validateCartItem(CartItemModel $item): CartItemValidationResult
    {
        $result = new CartItemValidationResult();

        if ($this->isCartItemInactive($item)) {
            $result->itemIsInactive();

            return $result;
        }

        $price = $item->child->product->getTypeInstance()->getFinalPrice($item->quantity);

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
     * Get product options.
     *
     * @param  string  $product
     * @return array
     */
    public function getProductOptions($product = '')
    {
        $configurableOption = app('Webkul\Product\Helpers\ConfigurableOption');
        $options = $configurableOption->getConfigurationConfig($product);

        return $options;
    }

    /**
     * Is product have sufficient quantity.
     *
     * @param  int  $qty
     * @return bool
     */
    public function haveSufficientQuantity(int $qty): bool
    {
        $isBackOrderEnable = (bool) core()->getConfigData('catalog.inventory.stock_options.backorders');

        foreach ($this->product->variants as $variant) {
            if ($variant->haveSufficientQuantity($qty)) {
                return true;
            }
        }

        return $isBackOrderEnable;
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

        $channelInventorySourceIds = core()->getCurrentChannel()
            ->inventory_sources
            ->where('status', 1)
            ->pluck('id');

        foreach ($this->product->variants as $variant) {
            foreach ($variant->inventories as $inventory) {
                if (is_numeric($index = $channelInventorySourceIds->search($inventory->inventory_source_id))) {
                    $total += $inventory->qty;
                }
            }

            $orderedInventory = $variant->ordered_inventories
                ->where('channel_id', core()->getCurrentChannel()->id)
                ->first();

            if ($orderedInventory) {
                $total -= $orderedInventory->qty;
            }
        }

        return $total;
    }
}
