<?php

namespace Webkul\Product\Type;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Webkul\Customer\Repositories\CustomerRepository;
use Webkul\Attribute\Repositories\AttributeRepository;
use Webkul\Product\Repositories\ProductRepository;
use Webkul\Product\Repositories\ProductAttributeValueRepository;
use Webkul\Product\Repositories\ProductInventoryRepository;
use Webkul\Product\Repositories\ProductImageRepository;
use Webkul\Product\Repositories\ProductVideoRepository;
use Webkul\Product\Repositories\ProductCustomerGroupPriceRepository;
use Webkul\Product\DataTypes\CartItemValidationResult;
use Webkul\Product\Models\ProductFlat;
use Webkul\Product\Facades\ProductImage;
use Webkul\Checkout\Facades\Cart;
use Webkul\Checkout\Models\CartItem;
use Webkul\Tax\Helpers\Tax;

abstract class AbstractType
{
    /**
     * Product instance.
     *
     * @var \Webkul\Product\Models\Product
     */
    protected $product;

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
    protected $isStockable = true;

    /**
     * Show quantity box.
     *
     * @var bool
     */
    protected $showQuantityBox = false;

    /**
     * Is product have sufficient quantity.
     *
     * @var bool
     */
    protected $haveSufficientQuantity = true;

    /**
     * Product can be moved from wishlist to cart or not.
     *
     * @var bool
     */
    protected $canBeMovedFromWishlistToCart = true;

    /**
     * Products of this type can be copied in the admin backend.
     *
     * @var bool
     */
    protected $canBeCopied = true;

    /**
     * Has child products aka variants.
     *
     * @var bool
     */
    protected $hasVariants = false;

    /**
     * Product children price can be calculated or not.
     *
     * @var bool
     */
    protected $isChildrenCalculated = false;

    /**
     * Skip attribute for simple product type.
     *
     * @var array
     */
    protected $skipAttributes = [];

    /**
     * These blade files will be included in product edit page.
     *
     * @var array
     */
    protected $additionalViews = [];

    /**
     * Create a new product type instance.
     *
     * @param  \Webkul\Customer\Repositories\CustomerRepository  $customerRepository
     * @param  \Webkul\Attribute\Repositories\AttributeRepository  $attributeRepository
     * @param  \Webkul\Product\Repositories\ProductRepository   $productRepository
     * @param  \Webkul\Product\Repositories\ProductAttributeValueRepository  $attributeValueRepository
     * @param  \Webkul\Product\Repositories\ProductInventoryRepository  $productInventoryRepository
     * @param  \Webkul\Product\Repositories\ProductImageRepository  $productImageRepository
     * @param  \Webkul\Product\Repositories\ProductVideoRepository  $productVideoRepository
     * @param  \Webkul\Product\Repositories\ProductCustomerGroupPriceRepository  $productCustomerGroupPriceRepository
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
        protected ProductCustomerGroupPriceRepository $productCustomerGroupPriceRepository
    )
    {
    }

    /**
     * Create product.
     *
     * @param  array  $data
     * @return \Webkul\Product\Contracts\Product
     */
    public function create(array $data)
    {
        return $this->productRepository->getModel()->create($data);
    }

    /**
     * Update product.
     *
     * @param  array  $data
     * @param  int  $id
     * @param  string  $attribute
     * @return \Webkul\Product\Contracts\Product
     */
    public function update(array $data, $id, $attribute = 'id')
    {
        $product = $this->productRepository->find($id);

        $product->update($data);

        $route = request()->route()?->getName();

        foreach ($product->attribute_family->custom_attributes as $attribute) {
            if (
                $attribute->type === 'boolean'
                && $route !== 'admin.catalog.products.mass_update'
            ) {
                $data[$attribute->code] = ! empty($data[$attribute->code]);
            }

            if (
                $attribute->type == 'multiselect'
                || $attribute->type == 'checkbox'
            ) {
                $data[$attribute->code] = isset($data[$attribute->code]) ? implode(',', $data[$attribute->code]) : null;
            }

            if (! isset($data[$attribute->code])) {
                continue;
            }

            if (
                $attribute->type === 'price'
                && empty($data[$attribute->code])
            ) {
                $data[$attribute->code] = null;
            }

            if (
                $attribute->type === 'date'
                && $data[$attribute->code] === ''
                && $route !== 'admin.catalog.products.mass_update'
            ) {
                $data[$attribute->code] = null;
            }

            if (
                $attribute->type === 'image'
                || $attribute->type === 'file'
            ) {
                $data[$attribute->code] = gettype($data[$attribute->code]) === 'object'
                    ? request()->file($attribute->code)->store('product/' . $product->id)
                    : $data[$attribute->code];
            }

            $attributeValues = $product->attribute_values
                ->where('attribute_id', $attribute->id);

            if ($attribute->value_per_channel) {
                if ($attribute->value_per_locale) {
                    $attributeValues = $attributeValues
                        ->where('channel', $attribute->value_per_channel ? $data['channel'] : null)
                        ->where('locale', $attribute->value_per_locale ? $data['locale'] : null);
                } else {
                    $attributeValues = $attributeValues
                        ->where('channel', $attribute->value_per_channel ? $data['channel'] : null);
                }
            } else {
                if ($attribute->value_per_locale) {
                    $attributeValues = $attributeValues
                        ->where('locale', $attribute->value_per_locale ? $data['locale'] : null);
                }
            }

            $attributeValue = $attributeValues->first();

            if (! $attributeValue) {
                $this->attributeValueRepository->create([
                    'product_id'            => $product->id,
                    'attribute_id'          => $attribute->id,
                    $attribute->column_name => $data[$attribute->code],
                    'channel'               => $attribute->value_per_channel ? $data['channel'] : null,
                    'locale'                => $attribute->value_per_locale ? $data['locale'] : null,
                ]);
            } else {
                $previousTextValue = $attributeValue->text_value;

                if (
                    $attribute->type == 'image'
                    || $attribute->type == 'file'
                ) {
                    /**
                     * If $data[$attribute->code]['delete'] is not empty, that means someone selected the "delete" option.
                     */
                    if (! empty($data[$attribute->code]['delete'])) {
                        Storage::delete($previousTextValue);

                        $data[$attribute->code] = null;
                    }
                    /**
                     * If $data[$attribute->code] is not equal to the previous one, that means someone has
                     * updated the file or image. In that case, we will remove the previous file.
                     */
                    else if (
                        ! empty($previousTextValue)
                        && $data[$attribute->code] != $previousTextValue
                    ) {
                        Storage::delete($previousTextValue);
                    }
                }

                $attributeValue->update([$attribute->column_name => $data[$attribute->code]]);
            }
        }

        if ($route == 'admin.catalog.products.mass_update') {
            return $product;
        }

        if (! isset($data['categories'])) {
            $data['categories'] = [];
        }

        $product->categories()->sync($data['categories']);

        $product->up_sells()->sync($data['up_sells'] ?? []);

        $product->cross_sells()->sync($data['cross_sells'] ?? []);

        $product->related_products()->sync($data['related_products'] ?? []);

        $this->productInventoryRepository->saveInventories($data, $product);

        $this->productImageRepository->uploadImages($data, $product);

        $this->productVideoRepository->uploadVideos($data, $product);

        $this->productCustomerGroupPriceRepository->saveCustomerGroupPrices(
            $data,
            $product
        );

        return $product;
    }

    /**
     * Copy product.
     *
     * @return \Webkul\Product\Contracts\Product
     * @throws \Exception
     */
    public function copy()
    {
        if (! $this->canBeCopied()) {
            throw new \Exception(trans('admin::app.response.product-can-not-be-copied', ['type' => $this->product->type]));
        }

        $copiedProduct = $this->product
            ->replicate()
            ->fill(['sku' => 'temporary-sku-' . substr(md5(microtime()), 0, 6)]);


        $copiedProduct->save();

        $this->copyAttributeValues($copiedProduct);

        $this->copyRelationships($copiedProduct);

        return $copiedProduct;
    }

    /**
     * Copy attribute values.
     *
     * @param  \Webkul\Product\Models\Product  $product
     * @return void
     */
    protected function copyAttributeValues($product): void
    {
        $productFlat = $this->product->product_flats[0]?->replicate() ?? new ProductFlat();

        $productFlat->product_id = $product->id;

        $attributesToSkip = config('products.skipAttributesOnCopy') ?? [];

        foreach ($this->product->attribute_values as $attributeValue) {
            $attribute = $attributeValue->attribute;

            if (in_array($attribute->code, $attributesToSkip)) {
                continue;
            }

            $value = null;

            if ($attribute->code == 'name') {
                $value = trans('admin::app.catalog.products.index.datagrid.copy-of', ['value' => $this->product->name]);
            } elseif ($attribute->code == 'url_key') {
                $value = trans('admin::app.catalog.products.index.datagrid.copy-of-slug', ['value' => $this->product->url_key]);
            } elseif ($attribute->code == 'sku') {
                $value = $product->sku;
            } elseif ($attribute->code === 'product_number') {
                if (!empty($this->product->product_number)) {
                    $value = trans('admin::app.catalog.products.index.datagrid.copy-of-slug', ['value' => $this->product->product_number]);
                }
            } elseif ($attribute->code == 'status') {
                $value = 0;
            }

            $newAttributeValue = $attributeValue->replicate();

            if (! is_null($value)) {
                $newAttributeValue->{$attribute->column_name} = $value;

                $productFlat->{$attribute->code} = $value;
            }

            $product->attribute_values()->save($newAttributeValue);
        }

        $productFlat->save();
    }

    /**
     * Copy relationships.
     *
     * @param  \Webkul\Product\Models\Product  $product
     * @return void
     */
    protected function copyRelationships($product)
    {
        $attributesToSkip = config('products.skipAttributesOnCopy') ?? [];

        if (! in_array('categories', $attributesToSkip)) {
            $product->categories()->sync($this->product->categories->pluck('id'));
        }

        if (! in_array('inventories', $attributesToSkip)) {
            foreach ($this->product->inventories as $inventory) {
                $product->inventories()->save($inventory->replicate());
            }
        }

        if (! in_array('customer_group_prices', $attributesToSkip)) {
            foreach ($this->product->customer_group_prices as $customer_group_price) {
                $product->customer_group_prices()->save($customer_group_price->replicate());
            }
        }

        if (! in_array('images', $attributesToSkip)) {
            foreach ($this->product->images as $image) {
                $copiedImage = $product->images()->save($image->replicate());

                $this->copyMedia($product, $image, $copiedImage);
            }
        }

        if (! in_array('videos', $attributesToSkip)) {
            foreach ($this->product->videos as $video) {
                $copiedVideo = $product->videos()->save($video->replicate());

                $this->copyMedia($product, $video, $copiedVideo);
            }
        }

        if (config('products.linkProductsOnCopy')) {
            DB::table('product_relations')->insert([
                'parent_id' => $this->product->id,
                'child_id'  => $product->id,
            ]);
        }
    }

    /**
     * Copy product image video.
     *
     * @param  $product
     * @param  $media
     * @param  $copiedMedia
     * @return void
     */
    private function copyMedia($product, $media, $copiedMedia): void
    {
        $path = explode('/', $media->path);

        $copiedMedia->path = 'product/' . $product->id . '/' . end($path);

        $copiedMedia->save();

        Storage::makeDirectory('product/' . $product->id);

        Storage::copy($media->path, $copiedMedia->path);
    }

    /**
     * Specify type instance product.
     *
     * @param  \Webkul\Product\Contracts\Product  $product
     * @return \Webkul\Product\Type\AbstractType
     */
    public function setProduct($product)
    {
        $this->product = $product;

        return $this;
    }

    /**
     * Returns children ids.
     *
     * @return array
     */
    public function getChildrenIds()
    {
        return [];
    }

    /**
     * Check if catalog rule can be applied.
     *
     * @return bool
     */
    public function priceRuleCanBeApplied()
    {
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

        if (
            is_callable(config('products.isSaleable')) &&
            call_user_func(config('products.isSaleable'), $this->product) === false
        ) {
            return false;
        }

        if (! $this->haveSufficientQuantity(1)) {
            return false;
        }

        return true;
    }

    /**
     * Return true if this product can have inventory.
     *
     * @return bool
     */
    public function isStockable()
    {
        return $this->isStockable;
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
     * Return true if this product can have variants.
     *
     * @return bool
     */
    public function hasVariants()
    {
        return $this->hasVariants;
    }

    /**
     * Product children price can be calculated or not.
     *
     * @return bool
     */
    public function isChildrenCalculated()
    {
        return $this->isChildrenCalculated;
    }

    /**
     * Is the administrator able to copy products of this type in the admin backend?
     *
     * @return bool
     */
    public function canBeCopied(): bool
    {
        return $this->canBeCopied;
    }

    /**
     * Have sufficient quantity.
     *
     * @param  int  $qty
     * @return bool
     */
    public function haveSufficientQuantity(int $qty): bool
    {
        return $this->haveSufficientQuantity;
    }

    /**
     * Return true if this product can have inventory.
     *
     * @return bool
     */
    public function showQuantityBox()
    {
        return $this->showQuantityBox;
    }

    /**
     * Is item have quantity.
     *
     * @param  \Webkul\Checkout\Contracts\CartItem  $cartItem
     * @return bool
     */
    public function isItemHaveQuantity($cartItem)
    {
        return $cartItem->getTypeInstance()->haveSufficientQuantity($cartItem->quantity);
    }

    /**
     * Total quantity.
     *
     * @return int
     */
    public function totalQuantity()
    {
        if (! $inventoryIndex = $this->getInventoryIndex()) {
            return 0;
        }

        return $inventoryIndex->qty;
    }

    /**
     * Return true if item can be moved to cart from wishlist.
     *
     * @param  \Webkul\Checkout\Contracts\CartItem  $item
     * @return bool
     */
    public function canBeMovedFromWishlistToCart($item)
    {
        return $this->canBeMovedFromWishlistToCart;
    }

    /**
     * Retrieve product attributes.
     *
     * @param  \Webkul\Attribute\Contracts\Group  $group
     * @param  bool  $skipSuperAttribute
     * @return \Illuminate\Support\Collection
     */
    public function getEditableAttributes($group = null, $skipSuperAttribute = true)
    {
        if ($skipSuperAttribute) {
            $this->skipAttributes = array_merge(
                $this->product->super_attributes->pluck('code')->toArray(),
                $this->skipAttributes
            );
        }

        if (! $group) {
            return $this->product->attribute_family->custom_attributes()->whereNotIn(
                'attributes.code',
                $this->skipAttributes
            )->get();
        }

        return $group->custom_attributes()->whereNotIn('code', $this->skipAttributes)->get();
    }

    /**
     * Returns additional views.
     *
     * @return array
     */
    public function getAdditionalViews()
    {
        return $this->additionalViews;
    }

    /**
     * Returns validation rules.
     *
     * @return array
     */
    public function getTypeValidationRules()
    {
        return [];
    }

    /**
     * Get product minimal price.
     *
     * @param  int  $qty
     * @return float
     */
    public function getMinimalPrice()
    {
        if (! $priceIndex = $this->getPriceIndex()) {
            return $this->product->price;
        }

        return $priceIndex->min_price;
    }

    /**
     * Get product regular minimal price.
     *
     * @return float
     */
    public function getRegularMinimalPrice()
    {
        if (! $priceIndex = $this->getPriceIndex()) {
            return $this->product->price;
        }

        return $priceIndex->regular_min_price;
    }

    /**
     * Get product maximum price.
     *
     * @return float
     */
    public function getMaximumPrice()
    {
        if (! $priceIndex = $this->getPriceIndex()) {
            return $this->product->price;
        }

        return $priceIndex->max_price;
    }

    /**
     * Get product regular minimal price.
     *
     * @return float
     */
    public function getRegularMaximumPrice()
    {
        if (! $priceIndex = $this->getPriceIndex()) {
            return $this->product->price;
        }

        return $priceIndex->regular_max_price;
    }

    /**
     * Get product minimal price.
     *
     * @param  int  $qty
     * @return float
     */
    public function getFinalPrice($qty = null)
    {
        if (
            is_null($qty)
            || $qty == 1
        ) {
            return $this->getMinimalPrice();
        }

        $customerGroup = $this->customerRepository->getCurrentGroup();

        $indexer = $this->getPriceIndexer()
            ->setCustomerGroup($customerGroup)
            ->setProduct($this->product);

        return $indexer->getMinimalPrice($qty);
    }

    /**
     * Returns product price index of current customer group.
     *
     * @return \Webkul\Product\Contracts\ProductPriceIndex
     */
    public function getPriceIndex()
    {
        $customerGroup = $this->customerRepository->getCurrentGroup();

        $indices = $this->product
            ->price_indices
            ->where('customer_group_id', $customerGroup->id)
            ->first();

        return $indices;
    }

    /**
     * Returns product inventory index of current channel.
     *
     * @return \Webkul\Product\Contracts\ProductInventoryIndex
     */
    public function getInventoryIndex()
    {
        $indices = $this->product
            ->inventory_indices
            ->where('channel_id', core()->getCurrentChannel()->id)
            ->first();

        return $indices;
    }

    /**
     * Have special price.
     *
     * @param  int  $qty
     * @return bool
     */
    public function haveDiscount($qty = null)
    {
        if (! $priceIndex = $this->getPriceIndex()) {
            return false;
        }

        return $priceIndex->min_price != $priceIndex->regular_min_price;
    }

    /**
     * Get product prices.
     *
     * @return array
     */
    public function getProductPrices()
    {
        return [
            'regular' => [
                'price'           => core()->convertPrice($regularPrice = $this->evaluatePrice($this->product->price)),
                'formatted_price' => core()->currency($regularPrice),
            ],

            'final'   => [
                'price'           => core()->convertPrice($minimalPrice = $this->evaluatePrice($this->getMinimalPrice())),
                'formatted_price' => core()->currency($minimalPrice),
            ],
        ];
    }

    /**
     * Get product price html.
     *
     * @return string
     */
    public function getPriceHtml()
    {
        return view('shop::products.prices.index', [
            'product' => $this->product,
            'prices'  => $this->getProductPrices(),
        ])->render();
    }

    /**
     * Get inclusive tax rates.
     *
     * @param  float  $totalPrice
     * @return float
     */
    public function getTaxInclusiveRate($totalPrice)
    {
        if (! $taxCategory = $this->getTaxCategory()) {
            return $totalPrice;
        }

        if (auth()->guard('customer')->check()) {
            $address = auth()->guard('customer')->user()->addresses->where('default_address', 1)->first();
        } else {
            $address = Tax::getDefaultAddress();
        }

        Tax::isTaxApplicableInCurrentAddress($taxCategory, $address, function ($rate) use (&$totalPrice) {
            $totalPrice = round($totalPrice, 4) + round(($totalPrice * $rate->tax_rate) / 100, 4);
        });

        return $totalPrice;
    }

    /**
     * Get tax category.
     *
     * @return \Webkul\Tax\Models\TaxCategory
     */
    public function getTaxCategory()
    {
        $taxCategoryId = $this->product->parent
            ? $this->product->parent->tax_category_id
            : $this->product->tax_category_id;

        return core()->getTaxCategoryById($taxCategoryId);
    }

    /**
     * Evaluate price.
     *
     * @return array
     */
    public function evaluatePrice($price)
    {
        $roundedOffPrice = round($price, 2);

        return Tax::isTaxInclusive()
            ? $this->getTaxInclusiveRate($roundedOffPrice)
            : $roundedOffPrice;
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
            return trans('shop::app.checkout.cart.inventory-warning');
        }

        $price = $this->getFinalPrice();

        $products = [
            [
                'product_id'        => $this->product->id,
                'sku'               => $this->product->sku,
                'quantity'          => $data['quantity'],
                'name'              => $this->product->name,
                'price'             => $convertedPrice = core()->convertPrice($price),
                'base_price'        => $price,
                'total'             => $convertedPrice * $data['quantity'],
                'base_total'        => $price * $data['quantity'],
                'weight'            => $this->product->weight ?? 0,
                'total_weight'      => ($this->product->weight ?? 0) * $data['quantity'],
                'base_total_weight' => ($this->product->weight ?? 0) * $data['quantity'],
                'type'              => $this->product->type,
                'additional'        => $this->getAdditionalOptions($data),
            ],
        ];

        return $products;
    }

    /**
     * Handle quantity.
     *
     * @param  int  $quantity
     * @return int
     */
    public function handleQuantity(int $quantity): int
    {
        return $quantity ?: 1;
    }

    /**
     * Get request quantity.
     *
     * @param  array  $data
     * @return array
     */
    public function getQtyRequest($data)
    {
        if ($item = Cart::getItemByProduct(['additional' => $data])) {
            $data['quantity'] += $item->quantity;
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
        if ($this->product->id != $options2['product_id']) {
            return false;
        } else {
            if (
                isset($options1['parent_id'])
                && isset($options2['parent_id'])
            ) {
                return $options1['parent_id'] == $options2['parent_id'];
            } elseif (
                isset($options1['parent_id'])
                && ! isset($options2['parent_id'])
            ) {
                return false;
            } elseif (
                isset($options2['parent_id'])
                && ! isset($options1['parent_id'])
            ) {
                return false;
            }
        }

        return true;
    }

    /**
     * Returns additional information for items.
     *
     * @param  array  $data
     * @return array
     */
    public function getAdditionalOptions($data)
    {
        return $data;
    }

    /**
     * Get actual ordered item.
     *
     * @param  \Webkul\Checkout\Contracts\CartItem $item
     * @return \Webkul\Checkout\Contracts\CartItem|\Webkul\Sales\Contracts\OrderItem|\Webkul\Sales\Contracts\InvoiceItem|\Webkul\Sales\Contracts\ShipmentItem|\Webkul\Customer\Contracts\Wishlist
     */
    public function getOrderedItem($item)
    {
        return $item;
    }

    /**
     * Get product base image.
     *
     * @param  \Webkul\Customer\Contracts\CartItem|\Webkul\Checkout\Contracts\CartItem $item
     * @return array
     */
    public function getBaseImage($item)
    {
        return ProductImage::getProductBaseImage($item->product);
    }

    /**
     * Validate cart item product price and other things.
     *
     * @param  \Webkul\Checkout\Models\CartItem  $item
     * @return \Webkul\Product\DataTypes\CartItemValidationResult
     */
    public function validateCartItem(CartItem $item): CartItemValidationResult
    {
        $result = new CartItemValidationResult();

        if ($this->isCartItemInactive($item)) {
            $result->itemIsInactive();

            return $result;
        }

        $price = round($this->getFinalPrice($item->quantity), 4);

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
     * Returns true, if cart item is inactive.
     *
     * @param  \Webkul\Checkout\Contracts\CartItem  $item
     * @return bool
     */
    public function isCartItemInactive(\Webkul\Checkout\Contracts\CartItem $item): bool
    {
        if (! $item->product->status) {
            return true;
        }

        switch ($item->product->type) {
            case 'bundle':
                foreach ($item->children as $child) {
                    if (! $child->product->status) {
                        return true;
                    }
                }

                break;

            case 'configurable':
                if (
                    $item->child
                    && ! $item->child->product->status
                ) {
                    return true;
                }

                break;
        }

        return false;
    }

    /**
     * Get more offers for customer group pricing.
     *
     * @return array
     */
    public function getCustomerGroupPricingOffers()
    {
        $offerLines = [];

        $customerGroup = $this->customerRepository->getCurrentGroup();

        $customerGroupPrices = $this->product->customer_group_prices()->where(function ($query) use ($customerGroup) {
                $query->where('customer_group_id', $customerGroup->id)
                    ->orWhereNull('customer_group_id');
            })
            ->where('qty', '>', 1)
            ->groupBy('qty')
            ->orderBy('qty')
            ->get();

        foreach ($customerGroupPrices as $customerGroupPrice) {
            if (
                ! is_null($this->product->special_price)
                && $customerGroupPrice->value >= $this->product->special_price
            ) {
                continue;
            }

            array_push($offerLines, $this->getOfferLines($customerGroupPrice));
        }

        return $offerLines;
    }

    /**
     * Get offers lines.
     *
     * @param  object  $customerGroupPrice
     * @return array
     */
    public function getOfferLines($customerGroupPrice)
    {
        $price = $this->getCustomerGroupPrice($this->product, $customerGroupPrice->qty);

        $discount = number_format((($this->product->price - $price) * 100) / ($this->product->price), 2);

        $offerLines = trans('shop::app.products.type.abstract.offers', [
            'qty'      => $customerGroupPrice->qty,
            'price'    => core()->currency($price),
            'discount' => $discount,
        ]);

        return $offerLines;
    }

    /**
     * Get product group price.
     *
     * @return float
     */
    public function getCustomerGroupPrice($product, $qty)
    {
        if (is_null($qty)) {
            $qty = 1;
        }

        $customerGroup = $this->customerRepository->getCurrentGroup();

        $customerGroupPrices = $this->productCustomerGroupPriceRepository->prices($product, $customerGroup->id);

        if ($customerGroupPrices->isEmpty()) {
            return $product->price;
        }

        $lastQty = 1;

        $lastPrice = $product->price;

        $lastCustomerGroupId = null;

        foreach ($customerGroupPrices as $customerGroupPrice) {
            if ($qty < $customerGroupPrice->qty) {
                continue;
            }

            if ($customerGroupPrice->qty < $lastQty) {
                continue;
            }

            if (
                $customerGroupPrice->qty == $lastQty
                && ! empty($lastCustomerGroupId)
                && empty($customerGroupPrice->customer_group_id)
            ) {
                continue;
            }

            if ($customerGroupPrice->value_type == 'discount') {
                if (
                    $customerGroupPrice->value >= 0
                    && $customerGroupPrice->value <= 100
                ) {
                    $lastPrice = $product->price - ($product->price * $customerGroupPrice->value) / 100;

                    $lastQty = $customerGroupPrice->qty;

                    $lastCustomerGroupId = $customerGroupPrice->customer_group_id;
                }
            } else {
                if (
                    $customerGroupPrice->value >= 0
                    && $customerGroupPrice->value < $lastPrice
                ) {
                    $lastPrice = $customerGroupPrice->value;

                    $lastQty = $customerGroupPrice->qty;

                    $lastCustomerGroupId = $customerGroupPrice->customer_group_id;
                }
            }
        }

        return $lastPrice;
    }
}
