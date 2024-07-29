<?php

namespace Webkul\Product\Type;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Webkul\Attribute\Repositories\AttributeRepository;
use Webkul\Checkout\Facades\Cart;
use Webkul\Checkout\Models\CartItem;
use Webkul\Customer\Repositories\CustomerRepository;
use Webkul\Product\DataTypes\CartItemValidationResult;
use Webkul\Product\Facades\ProductImage;
use Webkul\Product\Repositories\ProductAttributeValueRepository;
use Webkul\Product\Repositories\ProductCustomerGroupPriceRepository;
use Webkul\Product\Repositories\ProductImageRepository;
use Webkul\Product\Repositories\ProductInventoryRepository;
use Webkul\Product\Repositories\ProductRepository;
use Webkul\Product\Repositories\ProductVideoRepository;

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
     * Product can be added to cart with options or not.
     *
     * @var bool
     */
    protected $canBeAddedToCartWithoutOptions = true;

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
     * Attribute stored bu their code.
     *
     * @var array
     */
    protected $attributesByCode = [];

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
        protected ProductCustomerGroupPriceRepository $productCustomerGroupPriceRepository
    ) {}

    /**
     * Create product.
     *
     * @return \Webkul\Product\Contracts\Product
     */
    public function create(array $data)
    {
        $product = $this->productRepository->getModel()->create($data);

        $product->channels()->sync(core()->getDefaultChannel()->id);

        return $product;
    }

    /**
     * Update product.
     *
     * @param  int  $id
     * @param  array  $attributes
     * @return \Webkul\Product\Contracts\Product
     */
    public function update(array $data, $id, $attributes = [])
    {
        $product = $this->productRepository->find($id);

        $product->update($data);

        /**
         * If attributes are provided then only save the provided attributes and return.
         */
        if (! empty($attributes)) {
            $attributes = $this->attributeRepository->findWhereIn('code', $attributes);

            $this->attributeValueRepository->saveValues($data, $product, $attributes);

            return $product;
        }

        $this->attributeValueRepository->saveValues($data, $product, $product->attribute_family->custom_attributes);

        if (empty($data['channels'])) {
            $data['channels'][] = core()->getDefaultChannel()->id;
        }

        $product->channels()->sync($data['channels']);

        if (! isset($data['categories'])) {
            $data['categories'] = [];
        }

        $product->categories()->sync($data['categories']);

        $product->up_sells()->sync($data['up_sells'] ?? []);

        $product->cross_sells()->sync($data['cross_sells'] ?? []);

        $product->related_products()->sync($data['related_products'] ?? []);

        $this->productInventoryRepository->saveInventories($data, $product);

        $this->productImageRepository->upload($data, $product, 'images');

        $this->productVideoRepository->upload($data, $product, 'videos');

        $this->productCustomerGroupPriceRepository->saveCustomerGroupPrices($data, $product);

        return $product;
    }

    /**
     * @param  string  $code
     * @return \Webkul\Attribute\Contracts\Attribute
     */
    public function getAttributeByCode($code)
    {
        if (! empty($this->attributesByCode[$code])) {
            return $this->attributesByCode[$code];
        }

        return $this->attributesByCode[$code] = $this->attributeRepository->findOneByField('code', $code);
    }

    /**
     * Copy product.
     *
     * @return \Webkul\Product\Contracts\Product
     *
     * @throws \Exception
     */
    public function copy()
    {
        if (! $this->canBeCopied()) {
            throw new \Exception(trans('product::app.response.product-can-not-be-copied', ['type' => $this->product->type]));
        }

        $copiedProduct = $this->product
            ->replicate()
            ->fill(['sku' => 'temporary-sku-'.substr(md5(microtime()), 0, 6)]);

        $copiedProduct->save();

        $this->copyAttributeValues($copiedProduct);

        $this->copyRelationships($copiedProduct);

        return $copiedProduct;
    }

    /**
     * Copy attribute values.
     *
     * @param  \Webkul\Product\Models\Product  $product
     */
    protected function copyAttributeValues($product): void
    {
        $attributesToSkip = config('products.copy.skip_attributes') ?? [];

        $copyAttributes = [
            'name'           => trans('product::app.datagrid.copy-of', ['value' => $this->product->name]),
            'url_key'        => trans('product::app.datagrid.copy-of-slug', ['value' => $this->product->url_key]),
            'sku'            => $product->sku,
            'product_number' => ! empty($this->product->product_number) ? trans('product::app.datagrid.copy-of-slug', ['value' => $this->product->product_number]) : null,
            'status'         => 0,
        ];

        foreach ($this->product->attribute_values as $attributeValue) {
            $attribute = $attributeValue->attribute;

            if (in_array($attribute->code, $attributesToSkip)) {
                continue;
            }

            $value = $copyAttributes[$attribute->code] ?? null;

            $newAttributeValue = $attributeValue->replicate()->fill([
                'unique_id' => implode('|', array_filter([
                    $attributeValue->channel,
                    $attributeValue->locale,
                    $product->id,
                    $attribute->id,
                ])),
            ]);

            if (! is_null($value)) {
                $newAttributeValue->{$attribute->column_name} = $value;
            }

            $product->attribute_values()->save($newAttributeValue);
        }
    }

    /**
     * Copy relationships.
     *
     * @param  \Webkul\Product\Models\Product  $product
     * @return void
     */
    protected function copyRelationships($product)
    {
        $attributesToSkip = config('products.copy.skip_attributes') ?? [];

        if (! in_array('flat', $attributesToSkip)) {
            foreach ($this->product->product_flats as $productFlat) {
                $product->product_flats()->save($productFlat->replicate());
            }
        }

        if (! in_array('channels', $attributesToSkip)) {
            $product->channels()->sync($this->product->channels->pluck('id'));
        }

        if (! in_array('categories', $attributesToSkip)) {
            $product->categories()->sync($this->product->categories->pluck('id'));
        }

        if (! in_array('inventories', $attributesToSkip)) {
            foreach ($this->product->inventories as $inventory) {
                $product->inventories()->save($inventory->replicate());
            }
        }

        if (! in_array('customer_group_prices', $attributesToSkip)) {
            foreach ($this->product->customer_group_prices as $customerGroupPrice) {
                $product->customer_group_prices()->save($customerGroupPrice->replicate()->fill([
                    'unique_id' => implode('|', array_filter([
                        $customerGroupPrice->qty,
                        $product->id,
                        $customerGroupPrice->customer_group_id,
                    ])),
                ]));
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

        if (! in_array('product_relations', $attributesToSkip)) {
            DB::table('product_relations')->insert([
                'parent_id' => $this->product->id,
                'child_id'  => $product->id,
            ]);
        }
    }

    /**
     * Copy product image video.
     */
    private function copyMedia($product, $media, $copiedMedia): void
    {
        $path = explode('/', $media->path);

        $copiedMedia->path = 'product/'.$product->id.'/'.end($path);

        $copiedMedia->save();

        Storage::makeDirectory('product/'.$product->id);

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
     */
    public function canBeCopied(): bool
    {
        return $this->canBeCopied;
    }

    /**
     * Have sufficient quantity.
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
     * Return true if product can be added to cart without options.
     *
     * @return bool
     */
    public function canBeAddedToCartWithoutOptions()
    {
        return $this->canBeAddedToCartWithoutOptions;
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
            ->setChannel(core()->getCurrentChannel())
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
            ->where('channel_id', core()->getCurrentChannel()->id)
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
                'price'           => core()->convertPrice($this->product->price),
                'formatted_price' => core()->currency($this->product->price),
            ],

            'final'   => [
                'price'           => core()->convertPrice($minimalPrice = $this->getMinimalPrice()),
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
     * Get tax category.
     *
     * @return \Webkul\Tax\Models\TaxCategory
     */
    public function getTaxCategory()
    {
        $taxCategoryId = $this->product->parent?->tax_category_id ?? $this->product->tax_category_id;

        return core()->getTaxCategoryById($taxCategoryId);
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
                'weight'              => $this->product->weight ?? 0,
                'total_weight'        => ($this->product->weight ?? 0) * $data['quantity'],
                'base_total_weight'   => ($this->product->weight ?? 0) * $data['quantity'],
                'type'                => $this->product->type,
                'additional'          => $this->getAdditionalOptions($data),
            ],
        ];

        return $products;
    }

    /**
     * Handle quantity.
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
     * @param  \Webkul\Checkout\Contracts\CartItem  $item
     * @return \Webkul\Checkout\Contracts\CartItem|\Webkul\Sales\Contracts\OrderItem|\Webkul\Sales\Contracts\InvoiceItem|\Webkul\Sales\Contracts\ShipmentItem|\Webkul\Customer\Contracts\Wishlist
     */
    public function getOrderedItem($item)
    {
        return $item;
    }

    /**
     * Get product base image.
     *
     * @param  \Webkul\Customer\Contracts\CartItem|\Webkul\Checkout\Contracts\CartItem  $item
     * @return array
     */
    public function getBaseImage($item)
    {
        return ProductImage::getProductBaseImage($item->product);
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
     * Returns true, if cart item is inactive.
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

        $offerLines = trans('product::app.type.abstract.offers', [
            'qty'      => $customerGroupPrice->qty,
            'price'    => core()->currency($price),
            'discount' => '<span>'.$discount.'%</span>',
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
