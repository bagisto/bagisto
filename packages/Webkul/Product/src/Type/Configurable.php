<?php

namespace Webkul\Product\Type;

use Webkul\Product\Models\ProductAttributeValue;
use Webkul\Product\Models\ProductFlat;
use Illuminate\Support\Str;

/**
 * Class Configurable.
 *
 * @author    Jitendra Singh <jitendra@webkul.com>
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class Configurable extends AbstractType
{
    /**
     * Skip attribute for downloadable product type
     *
     * @var array
     */
    protected $skipAttributes = ['price', 'cost', 'special_price', 'special_price_from', 'special_price_to', 'width', 'height', 'depth', 'weight'];

    /**
     * These blade files will be included in product edit page
     *
     * @var array
     */
    protected $additionalViews = [
        'admin::catalog.products.accordians.images',
        'admin::catalog.products.accordians.categories',
        'admin::catalog.products.accordians.variations',
        'admin::catalog.products.accordians.channels',
        'admin::catalog.products.accordians.product-links'
    ];

    /**
     * Is a composite product type
     *
     * @var boolean
     */
    protected $isComposite = true;

    /**
     * Show quantity box
     *
     * @var boolean
     */
    protected $showQuantityBox = true;

    /**
     * Has child products aka variants
     *
     * @var boolean
     */
    protected $hasVariants = true;

    /**
     * @param array $data
     * @return Product
     */
    public function create(array $data)
    {
        $product = $this->productRepository->getModel()->create($data);

        if (isset($data['super_attributes'])) {
            $super_attributes = [];

            foreach ($data['super_attributes'] as $attributeCode => $attributeOptions) {
                $attribute = $this->attributeRepository->findOneByField('code', $attributeCode);

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
     * @param array $data
     * @param $id
     * @param string $attribute
     * @return Product
     */
    public function update(array $data, $id, $attribute = "id")
    {
        $product = parent::update($data, $id, $attribute);

        if (request()->route()->getName() != 'admin.catalog.products.massupdate') {
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
                        if (is_numeric($index = $previousVariantIds->search($variantId)))
                            $previousVariantIds->forget($index);

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
     * @param mixed $product
     * @param array $permutation
     * @param array $data
     * @return mixed
     */
    public function createVariant($product, $permutation, $data = [])
    {
        if (! count($data)) {
            $data = [
                    "sku" => $product->sku . '-variant-' . implode('-', $permutation),
                    "name" => "",
                    "inventories" => [],
                    "price" => 0,
                    "weight" => 0,
                    "status" => 1
                ];
        }

        $typeOfVariants = 'simple';
        $productInstance = app(config('product_types.' . $product->type . '.class'));
        if (isset($productInstance->variantsType) && ! in_array($productInstance->variantsType , ['bundle', 'configurable', 'grouped'])) {
            $typeOfVariants = $productInstance->variantsType;
        }

        $variant = $this->productRepository->getModel()->create([
                'parent_id' => $product->id,
                'type' => $typeOfVariants,
                'attribute_family_id' => $product->attribute_family_id,
                'sku' => $data['sku'],
            ]);

        foreach (['sku', 'name', 'price', 'weight', 'status'] as $attributeCode) {
            $attribute = $this->attributeRepository->findOneByField('code', $attributeCode);

            if ($attribute->value_per_channel) {
                if ($attribute->value_per_locale) {
                    foreach (core()->getAllChannels() as $channel) {
                        foreach (core()->getAllLocales() as $locale) {
                            $this->attributeValueRepository->create([
                                    'product_id' => $variant->id,
                                    'attribute_id' => $attribute->id,
                                    'channel' => $channel->code,
                                    'locale' => $locale->code,
                                    'value' => $data[$attributeCode]
                                ]);
                        }
                    }
                } else {
                    foreach (core()->getAllChannels() as $channel) {
                        $this->attributeValueRepository->create([
                                'product_id' => $variant->id,
                                'attribute_id' => $attribute->id,
                                'channel' => $channel->code,
                                'value' => $data[$attributeCode]
                            ]);
                    }
                }
            } else {
                if ($attribute->value_per_locale) {
                    foreach (core()->getAllLocales() as $locale) {
                        $this->attributeValueRepository->create([
                                'product_id' => $variant->id,
                                'attribute_id' => $attribute->id,
                                'locale' => $locale->code,
                                'value' => $data[$attributeCode]
                            ]);
                    }
                } else {
                    $this->attributeValueRepository->create([
                            'product_id' => $variant->id,
                            'attribute_id' => $attribute->id,
                            'value' => $data[$attributeCode]
                        ]);
                }
            }
        }

        foreach ($permutation as $attributeId => $optionId) {
            $this->attributeValueRepository->create([
                    'product_id' => $variant->id,
                    'attribute_id' => $attributeId,
                    'value' => $optionId
                ]);
        }

        $this->productInventoryRepository->saveInventories($data, $variant);

        return $variant;
    }

    /**
     * @param array $data
     * @param $id
     * @return mixed
     */
    public function updateVariant(array $data, $id)
    {
        $variant = $this->productRepository->find($id);

        $variant->update(['sku' => $data['sku']]);

        foreach (['sku', 'name', 'price', 'weight', 'status'] as $attributeCode) {
            $attribute = $this->attributeRepository->findOneByField('code', $attributeCode);

            $attributeValue = $this->attributeValueRepository->findOneWhere([
                    'product_id' => $id,
                    'attribute_id' => $attribute->id,
                    'channel' => $attribute->value_per_channel ? $data['channel'] : null,
                    'locale' => $attribute->value_per_locale ? $data['locale'] : null
                ]);

            if (! $attributeValue) {
                $this->attributeValueRepository->create([
                        'product_id' => $id,
                        'attribute_id' => $attribute->id,
                        'value' => $data[$attribute->code],
                        'channel' => $attribute->value_per_channel ? $data['channel'] : null,
                        'locale' => $attribute->value_per_locale ? $data['locale'] : null
                    ]);
            } else {
                $this->attributeValueRepository->update([
                    ProductAttributeValue::$attributeTypeFields[$attribute->type] => $data[$attribute->code]
                ], $attributeValue->id);
            }
        }

        $this->productInventoryRepository->saveInventories($data, $variant);

        return $variant;
    }

    /**
     * @param array $data
     * @param mixed $product
     * @return mixed
     */
    public function checkVariantOptionAvailabiliy($data, $product)
    {
        $superAttributeCodes = $product->parent->super_attributes->pluck('code');

        foreach ($product->parent->variants as $variant) {
            if ($variant->id == $product->id)
                continue;

            $matchCount = 0;

            foreach ($superAttributeCodes as $attributeCode) {
                if (! isset($data[$attributeCode]))
                    return false;

                if ($data[$attributeCode] == $variant->{$attributeCode})
                    $matchCount++;
            }

            if ($matchCount == $superAttributeCodes->count())
                return true;
        }

        return false;
    }

    /**
     * Returns children ids
     *
     * @return array
     */
    public function getChildrenIds()
    {
        return $this->product->variants()->pluck('id')->toArray();
    }

    /**
     * @param CartItem $cartItem
     * @return bool
     */
    public function isItemHaveQuantity($cartItem)
    {
        return $cartItem->child->product->getTypeInstance()->haveSufficientQuantity($cartItem->quantity);
    }

    /**
     * Returns validation rules
     *
     * @return array
     */
    public function getTypeValidationRules()
    {
        return [
            'variants.*.name' => 'required',
            'variants.*.sku' => 'required',
            'variants.*.price' => 'required',
            'variants.*.weight' => 'required',
        ];
    }

    /**
     * Return true if item can be moved to cart from wishlist
     *
     * @param Wishlist $item
     * @return boolean
     */
    public function canBeMovedFromWishlistToCart($item)
    {
        if (isset($item->additional['selected_configurable_option']))
            return true;

        return false;
    }

    /**
     * Get product minimal price
     *
     * @return float
     */
    public function getMinimalPrice()
    {
        $minPrices = [];

        $result = ProductFlat::join('products', 'product_flat.product_id', '=', 'products.id')
            ->distinct()
            ->where('products.parent_id', $this->product->id)
            ->selectRaw('IF( product_flat.special_price_from IS NOT NULL
            AND product_flat.special_price_to IS NOT NULL , IF( NOW( ) >= product_flat.special_price_from
            AND NOW( ) <= product_flat.special_price_to, IF( product_flat.special_price IS NULL OR product_flat.special_price = 0 , product_flat.price, LEAST( product_flat.special_price, product_flat.price ) ) , product_flat.price ) , IF( product_flat.special_price_from IS NULL , IF( product_flat.special_price_to IS NULL , IF( product_flat.special_price IS NULL OR product_flat.special_price = 0 , product_flat.price, LEAST( product_flat.special_price, product_flat.price ) ) , IF( NOW( ) <= product_flat.special_price_to, IF( product_flat.special_price IS NULL OR product_flat.special_price = 0 , product_flat.price, LEAST( product_flat.special_price, product_flat.price ) ) , product_flat.price ) ) , IF( product_flat.special_price_to IS NULL , IF( NOW( ) >= product_flat.special_price_from, IF( product_flat.special_price IS NULL OR product_flat.special_price = 0 , product_flat.price, LEAST( product_flat.special_price, product_flat.price ) ) , product_flat.price ) , product_flat.price ) ) ) AS min_price')
            ->where('product_flat.channel', core()->getCurrentChannelCode())
            ->where('product_flat.locale', app()->getLocale())
            ->get();

        foreach ($result as $price) {
            $minPrices[] = $price->min_price;
        }

        if (empty($minPrices))
            return 0;

        return min($minPrices);
    }

    /**
     * Get product maximam price
     *
     * @return float
     */
    public function getMaximamPrice()
    {
        $productFlat = ProductFlat::join('products', 'product_flat.product_id', '=', 'products.id')
            ->distinct()
            ->where('products.parent_id', $this->product->id)
            ->selectRaw('MAX(product_flat.price) AS max_price')
            ->where('product_flat.channel', core()->getCurrentChannelCode())
            ->where('product_flat.locale', app()->getLocale())
            ->first();

        return $productFlat ? $productFlat->max_price : 0;
    }

    /**
     * Get product minimal price
     *
     * @return string
     */
    public function getPriceHtml()
    {
        return '<span class="price-label">' . trans('shop::app.products.price-label') . '</span>'
            . '<span class="final-price">' . core()->currency($this->getMinimalPrice()) . '</span>';
    }

    /**
     * Add product. Returns error message if can't prepare product.
     *
     * @param array   $data
     * @return array
     */
    public function prepareForCart($data)
    {
        if (! isset($data['selected_configurable_option']) || ! $data['selected_configurable_option'])
            return trans('shop::app.checkout.cart.integrity.missing_options');

        $data = $this->getQtyRequest($data);

        $childProduct = $this->productRepository->find($data['selected_configurable_option']);

        if (! $childProduct->haveSufficientQuantity($data['quantity']))
            return trans('shop::app.checkout.cart.quantity.inventory_warning');

        $price = $childProduct->getTypeInstance()->getFinalPrice();

        $products = [
            [
                'product_id' => $this->product->id,
                'sku' => $this->product->sku,
                'quantity' => $data['quantity'],
                'name' => $this->product->name,
                'price' => $convertedPrice = core()->convertPrice($price),
                'base_price' => $price,
                'total' => $convertedPrice * $data['quantity'],
                'base_total' => $price * $data['quantity'],
                'weight' => $childProduct->weight,
                'total_weight' => $childProduct->weight * $data['quantity'],
                'base_total_weight' => $childProduct->weight * $data['quantity'],
                'type' => $this->product->type,
                'additional' => $this->getAdditionalOptions($data)
            ], [
                'parent_id' => $this->product->id,
                'product_id' => (int) $data['selected_configurable_option'],
                'sku' => $childProduct->sku,
                'name' => $childProduct->name,
                'type' => 'simple',
                'additional' => ['product_id' => (int) $data['selected_configurable_option'], 'parent_id' => $this->product->id]
            ]
        ];

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

        return $options1['selected_configurable_option'] === $options2['selected_configurable_option'];
    }

    /**
     * Returns additional information for items
     *
     * @param array $data
     * @return array
     */
    public function getAdditionalOptions($data)
    {
        $childProduct = app('Webkul\Product\Repositories\ProductRepository')->findOneByField('id', $data['selected_configurable_option']);

        foreach ($this->product->super_attributes as $attribute) {
            $option = $attribute->options()->where('id', $childProduct->{$attribute->code})->first();

            $data['attributes'][$attribute->code] = [
                'attribute_name' => $attribute->name ?  $attribute->name : $attribute->admin_name,
                'option_id' => $option->id,
                'option_label' => $option->label ? $option->label : $option->admin_name,
            ];
        }

        return $data;
    }

    /**
     * Get actual ordered item
     *
     * @param CartItem $item
     * @return CartItem|OrderItem|InvoiceItem|ShipmentItem
     */
    public function getOrderedItem($item)
    {
        return $item->child;
    }

    /**
     * Get product base image
     *
     * @param Wishlist|CartItem $item
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
            $product = $item->child->product;
        }

        return $this->productImageHelper->getProductBaseImage($product);
    }

    /**
     * Validate cart item product price
     *
     * @param CartItem $item
     * @return float
     */
    public function validateCartItem($item)
    {
        $price = $item->child->product->getTypeInstance()->getFinalPrice();

        if ($price == $item->base_price)
            return;

        $item->base_price = $price;
        $item->price = core()->convertPrice($price);

        $item->base_total = $price * $item->quantity;
        $item->total = core()->convertPrice($price * $item->quantity);

        $item->save();
    }
}