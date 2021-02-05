<?php

namespace Webkul\Product\Type;

use Webkul\Checkout\Facades\Cart;
use Webkul\Checkout\Models\CartItem;
use Illuminate\Support\Facades\Storage;
use Webkul\Product\Facades\ProductImage;
use Webkul\Product\Models\ProductAttributeValue;
use Webkul\Product\Repositories\ProductRepository;
use Webkul\Attribute\Repositories\AttributeRepository;
use Webkul\Product\Datatypes\CartItemValidationResult;
use Webkul\Product\Repositories\ProductImageRepository;
use Webkul\Product\Repositories\ProductVideoRepository;
use Webkul\Product\Repositories\ProductInventoryRepository;
use Webkul\Product\Repositories\ProductAttributeValueRepository;

abstract class AbstractType
{
    /**
     * AttributeRepository instance
     *
     * @var \Webkul\Attribute\Repositories\AttributeRepository
     */
    protected $attributeRepository;

    /**
     * ProductRepository instance
     *
     * @var \Webkul\Product\Repositories\ProductRepository
     */
    protected $productRepository;

    /**
     * ProductAttributeValueRepository instance
     *
     * @var \Webkul\Product\Repositories\ProductAttributeValueRepository
     */
    protected $attributeValueRepository;

    /**
     * ProductInventoryRepository instance
     *
     * @var ProductInventoryRepository
     */
    protected $productInventoryRepository;

    /**
     * ProductImageRepository instance
     *
     * @var \Webkul\Product\Repositories\productImageRepository
     */
    protected $productImageRepository;

    /**
     * ProductVideoRepository instance
     *
     * @var \Webkul\Product\Repositories\productVideoRepository
     */
    protected $productVideoRepository;

    /**
     * Product model instance
     *
     * @var \Webkul\Product\Contracts\Product
     */
    protected $product;

    /**
     * Is a composite product type
     *
     * @var bool
     */
    protected $isComposite = false;

    /**
     * Is a stokable product type
     *
     * @var bool
     */
    protected $isStockable = true;

    /**
     * Show quantity box
     *
     * @var bool
     */
    protected $showQuantityBox = false;

    /**
     *
     * @var bool
     */
    protected $allowMultipleQty = true;

    /**
     * Is product have sufficient quantity
     *
     * @var bool
     */
    protected $haveSufficientQuantity = true;

    /**
     * Product can be moved from wishlist to cart or not
     *
     * @var bool
     */
    protected $canBeMovedFromWishlistToCart = true;

    /**
     * Products of this type can be copied in the admin backend?
     *
     * @var bool
     */
    protected $canBeCopied = true;

    /**
     * Has child products aka variants
     *
     * @var bool
     */
    protected $hasVariants = false;

    /**
     * Product children price can be calculated or not
     *
     * @var bool
     */
    protected $isChildrenCalculated = false;

    /**
     * product options
     */
    protected $productOptions = [];

    /**
     * Create a new product type instance.
     *
     * @param \Webkul\Attribute\Repositories\AttributeRepository           $attributeRepository
     * @param \Webkul\Product\Repositories\ProductRepository               $productRepository
     * @param \Webkul\Product\Repositories\ProductAttributeValueRepository $attributeValueRepository
     * @param \Webkul\Product\Repositories\ProductInventoryRepository      $productInventoryRepository
     * @param \Webkul\Product\Repositories\ProductImageRepository          $productImageRepository
     * @param \Webkul\Product\Repositories\ProductVideoRepository          $productVideoRepository
     *
     * @return void
     */
    public function __construct(
        AttributeRepository $attributeRepository,
        ProductRepository $productRepository,
        ProductAttributeValueRepository $attributeValueRepository,
        ProductInventoryRepository $productInventoryRepository,
        ProductImageRepository $productImageRepository,
        ProductVideoRepository $productVideoRepository
    ) {
        $this->attributeRepository = $attributeRepository;

        $this->productRepository = $productRepository;

        $this->attributeValueRepository = $attributeValueRepository;

        $this->productInventoryRepository = $productInventoryRepository;

        $this->productImageRepository = $productImageRepository;

        $this->productVideoRepository = $productVideoRepository;
    }

    /**
     * Is the administrator able to copy products of this type in the admin backend?
     */
    public function canBeCopied(): bool
    {
        return $this->canBeCopied;
    }

    /**
     * @param array $data
     *
     * @return \Webkul\Product\Contracts\Product
     */
    public function create(array $data)
    {
        return $this->productRepository->getModel()->create($data);
    }

    /**
     * @param array  $data
     * @param int    $id
     * @param string $attribute
     *
     * @return \Webkul\Product\Contracts\Product
     */
    public function update(array $data, $id, $attribute = "id")
    {
        $product = $this->productRepository->find($id);

        $product->update($data);

        foreach ($product->attribute_family->custom_attributes as $attribute) {
            $route = request()->route() ? request()->route()->getName() : "";

            if ($attribute->type === 'boolean' && $route !== 'admin.catalog.products.massupdate') {
                $data[$attribute->code] = isset($data[$attribute->code]) && $data[$attribute->code] ? 1 : 0;
            }

            if (!isset($data[$attribute->code])) {
                continue;
            }

            if ($attribute->type === 'price' && isset($data[$attribute->code]) && $data[$attribute->code] === '') {
                $data[$attribute->code] = null;
            }

            if ($attribute->type === 'date' && $data[$attribute->code] === '' && $route !== 'admin.catalog.products.massupdate') {
                $data[$attribute->code] = null;
            }

            if ($attribute->type === 'multiselect' || $attribute->type === 'checkbox') {
                $data[$attribute->code] = implode(",", $data[$attribute->code]);
            }

            if ($attribute->type === 'image' || $attribute->type === 'file') {
                $data[$attribute->code] = gettype($data[$attribute->code]) === 'object'
                    ? request()->file($attribute->code)->store('product/' . $product->id)
                    : null;
            }

            $attributeValue = $this->attributeValueRepository->findOneWhere([
                'product_id'   => $product->id,
                'attribute_id' => $attribute->id,
                'channel'      => $attribute->value_per_channel ? $data['channel'] : null,
                'locale'       => $attribute->value_per_locale ? $data['locale'] : null,
            ]);

            if (!$attributeValue) {
                $this->attributeValueRepository->create([
                    'product_id'   => $product->id,
                    'attribute_id' => $attribute->id,
                    'value'        => $data[$attribute->code],
                    'channel'      => $attribute->value_per_channel ? $data['channel'] : null,
                    'locale'       => $attribute->value_per_locale ? $data['locale'] : null,
                ]);
            } else {
                $this->attributeValueRepository->update([
                    ProductAttributeValue::$attributeTypeFields[$attribute->type] => $data[$attribute->code],
                ], $attributeValue->id
                );

                if ($attribute->type == 'image' || $attribute->type == 'file') {
                    Storage::delete($attributeValue->text_value);
                }
            }
        }

        $route = request()->route() ? request()->route()->getName() : "";

        if ($route !== 'admin.catalog.products.massupdate') {
            if (! isset($data['categories'])) {
                $data['categories'] = [];
            }

            $product->categories()->sync($data['categories']);

            $product->up_sells()->sync($data['up_sell'] ?? []);

            $product->cross_sells()->sync($data['cross_sell'] ?? []);

            $product->related_products()->sync($data['related_products'] ?? []);

            $this->productInventoryRepository->saveInventories($data, $product);

            $this->productImageRepository->uploadImages($data, $product);

            $this->productVideoRepository->uploadVideos($data, $product);

            app('Webkul\Product\Repositories\ProductCustomerGroupPriceRepository')->saveCustomerGroupPrices($data,
                $product);
        }

        return $product;
    }

    /**
     * Specify type instance product
     *
     * @param \Webkul\Product\Contracts\Product $product
     *
     * @return \Webkul\Product\Type\AbstractType
     */
    public function setProduct($product)
    {
        $this->product = $product;

        return $this;
    }

    /**
     * Returns children ids
     *
     * @return array
     */
    public function getChildrenIds()
    {
        return [];
    }

    /**
     * Check if catalog rule can be applied
     *
     * @return bool
     */
    public function priceRuleCanBeApplied()
    {
        return true;
    }

    /**
     * Return true if this product type is saleable
     *
     * @return bool
     */
    public function isSaleable()
    {
        if (!$this->product->status) {
            return false;
        }

        if (is_callable(config('products.isSaleable')) &&
            call_user_func(config('products.isSaleable'), $this->product) === false) {
            return false;
        }

        return true;
    }

    /**
     * Return true if this product can have inventory
     *
     * @return bool
     */
    public function isStockable()
    {
        return $this->isStockable;
    }

    /**
     * Return true if this product can be composite
     *
     * @return bool
     */
    public function isComposite()
    {
        return $this->isComposite;
    }

    /**
     * Return true if this product can have variants
     *
     * @return bool
     */
    public function hasVariants()
    {
        return $this->hasVariants;
    }

    /**
     * Product children price can be calculated or not
     *
     * @return bool
     */
    public function isChildrenCalculated()
    {
        return $this->isChildrenCalculated;
    }

    /**
     * @param int $qty
     *
     * @return bool
     */
    public function haveSufficientQuantity(int $qty): bool
    {
        return $this->haveSufficientQuantity;
    }

    /**
     * Return true if this product can have inventory
     *
     * @return bool
     */
    public function showQuantityBox()
    {
        return $this->showQuantityBox;
    }

    /**
     * Return true if more than one qty can be added to cart
     *
     * @return bool
     */
    public function isMultipleQtyAllowed()
    {
        return $this->allowMultipleQty;
    }

    /**
     * @param \Webkul\Checkout\Contracts\CartItem $cartItem
     *
     * @return bool
     */
    public function isItemHaveQuantity($cartItem)
    {
        return $cartItem->product->getTypeInstance()->haveSufficientQuantity($cartItem->quantity);
    }

    /**
     * @return int
     */
    public function totalQuantity()
    {
        $total = 0;

        $channelInventorySourceIds = core()->getCurrentChannel()
            ->inventory_sources()
            ->where('status', 1)
            ->pluck('id');

        foreach ($this->product->inventories as $inventory) {
            if (is_numeric($index = $channelInventorySourceIds->search($inventory->inventory_source_id))) {
                $total += $inventory->qty;
            }
        }

        $orderedInventory = $this->product->ordered_inventories()
            ->where('channel_id', core()->getCurrentChannel()->id)
            ->first();

        if ($orderedInventory) {
            $total -= $orderedInventory->qty;
        }

        return $total;
    }

    /**
     * Return true if item can be moved to cart from wishlist
     *
     * @param \Webkul\Checkout\Contracts\CartItem $item
     *
     * @return bool
     */
    public function canBeMovedFromWishlistToCart($item)
    {
        return $this->canBeMovedFromWishlistToCart;
    }

    /**
     * Retrieve product attributes
     *
     * @param \Webkul\Attribute\Contracts\Group $group
     * @param bool                              $skipSuperAttribute
     *
     * @return \Illuminate\Support\Collection
     */
    public function getEditableAttributes($group = null, $skipSuperAttribute = true)
    {
        if ($skipSuperAttribute) {
            $this->skipAttributes = array_merge($this->product->super_attributes->pluck('code')->toArray(),
                $this->skipAttributes);
        }

        if (!$group) {
            return $this->product->attribute_family->custom_attributes()->whereNotIn('attributes.code',
                $this->skipAttributes)->get();
        }

        return $group->custom_attributes()->whereNotIn('code', $this->skipAttributes)->get();
    }

    /**
     * Returns additional views
     *
     * @return array
     */
    public function getAdditionalViews()
    {
        return $this->additionalViews;
    }

    /**
     * Returns validation rules
     *
     * @return array
     */
    public function getTypeValidationRules()
    {
        return [];
    }

    /**
     * Get product minimal price
     *
     * @param int $qty
     *
     * @return float
     */
    public function getMinimalPrice($qty = null)
    {
        if ($this->haveSpecialPrice($qty)) {
            return $this->product->special_price;
        }

        return $this->product->price;
    }

    /**
     * Get product maximam price
     *
     * @return float
     */
    public function getMaximamPrice()
    {
        return $this->getMinimalPrice();
    }

    /**
     * Get product minimal price
     *
     * @param int $qty
     *
     * @return float
     */
    public function getFinalPrice($qty = null)
    {
        return $this->getMinimalPrice($qty);
    }

    /**
     * Returns the product's minimal price
     *
     * @param int $qty
     *
     * @return float
     */
    public function getSpecialPrice($qty = null)
    {
        return $this->haveSpecialPrice($qty) ? $this->product->special_price : $this->product->price;
    }

    /**
     * @param int $qty
     *
     * @return bool
     */
    public function haveSpecialPrice($qty = null)
    {
        $customerGroupPrice = $this->getCustomerGroupPrice($this->product, $qty);

        $rulePrice = app('Webkul\CatalogRule\Helpers\CatalogRuleProductPrice')->getRulePrice($this->product);

        if ((is_null($this->product->special_price) || ! (float)$this->product->special_price)
            && ! $rulePrice
            && $customerGroupPrice == $this->product->price
        ) {
            return false;
        }

        $haveSpecialPrice = false;

        if (! (float)$this->product->special_price) {
            if ($rulePrice && $rulePrice->price < $this->product->price) {
                $this->product->special_price = $rulePrice->price;

                $haveSpecialPrice = true;
            }
        } else {
            if ($rulePrice && $rulePrice->price <= $this->product->special_price) {
                $this->product->special_price = $rulePrice->price;

                $haveSpecialPrice = true;
            } else {
                if (core()->isChannelDateInInterval($this->product->special_price_from,
                    $this->product->special_price_to)) {
                    $haveSpecialPrice = true;
                } elseif ($rulePrice) {
                    $this->product->special_price = $rulePrice->price;

                    $haveSpecialPrice = true;
                }
            }
        }

        if ($haveSpecialPrice) {
            $this->product->special_price = min($this->product->special_price, $customerGroupPrice);
        } else {
            if ($customerGroupPrice !== $this->product->price) {
                $haveSpecialPrice = true;
                $this->product->special_price = $customerGroupPrice;
            }
        }

        return $haveSpecialPrice;
    }

    /**
     * Get product group price
     *
     * @return float
     */
    public function getCustomerGroupPrice($product, $qty)
    {
        if (is_null($qty)) {
            $qty = 1;
        }

        $customerGroupId = null;

        if (Cart::getCurrentCustomer()->check()) {
            $customerGroupId = Cart::getCurrentCustomer()->user()->customer_group_id;
        } else {
            $customerGroupRepository = app('Webkul\Customer\Repositories\CustomerGroupRepository');

            if ($customerGuestGroup = $customerGroupRepository->findOneByField('code', 'guest')) {
                $customerGroupId = $customerGuestGroup->id;
            }
        }

        $customerGroupPrices = $product->customer_group_prices()->where(function ($query) use ($customerGroupId) {
            $query->where('customer_group_id', $customerGroupId)
                ->orWhereNull('customer_group_id');
        }
        )->get();

        if (!$customerGroupPrices->count()) {
            return $product->price;
        }

        $lastQty = 1;

        $lastPrice = $product->price;

        $lastCustomerGroupId = null;

        foreach ($customerGroupPrices as $price) {
            if ($price->customer_group_id != $customerGroupId && $price->customer_group_id) {
                continue;
            }

            if ($qty < $price->qty) {
                continue;
            }

            if ($price->qty < $lastQty) {
                continue;
            }

            if ($price->qty == $lastQty
                && $lastCustomerGroupId != null
                && $price->customer_group_id == null
            ) {
                continue;
            }

            if ($price->value_type == 'discount') {
                if ($price->value >= 0 && $price->value <= 100) {
                    $lastPrice = $product->price - ($product->price * $price->value) / 100;

                    $lastQty = $price->qty;

                    $lastCustomerGroupId = $price->customer_group_id;
                }
            } else {
                if ($price->value >= 0 && $price->value < $lastPrice) {
                    $lastPrice = $price->value;

                    $lastQty = $price->qty;

                    $lastCustomerGroupId = $price->customer_group_id;
                }
            }
        }

        return $lastPrice;
    }

    /**
     * Returns product prices
     *
     * @return array
     */
    public function getProductPrices()
    {
        return [
            'regular_price' => [
                'price'          => core()->convertPrice($this->product->price),
                'formated_price' => core()->currency($this->product->price),
            ],
            'final_price'   => [
                'price'          => core()->convertPrice($this->getMinimalPrice()),
                'formated_price' => core()->currency($this->getMinimalPrice()),
            ],
        ];
    }

    /**
     * Get product minimal price
     *
     * @return string
     */
    public function getPriceHtml()
    {
        if ($this->haveSpecialPrice()) {
            $html = '<div class="sticker sale">' . trans('shop::app.products.sale') . '</div>'
                . '<span class="regular-price">' . core()->currency($this->product->price) . '</span>'
                . '<span class="special-price">' . core()->currency($this->getSpecialPrice()) . '</span>';
        } else {
            $html = '<span>' . core()->currency($this->product->price) . '</span>';
        }

        return $html;
    }

    /**
     * Add product. Returns error message if can't prepare product.
     *
     * @param array $data
     *
     * @return array
     */
    public function prepareForCart($data)
    {
        $data['quantity'] = $data['quantity'] ?? 1;

        $data = $this->getQtyRequest($data);

        if (! $this->haveSufficientQuantity($data['quantity'])) {
            return trans('shop::app.checkout.cart.quantity.inventory_warning');
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
     * Get request quantity
     *
     * @param array $data
     *
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
     *
     * @param array $options1
     * @param array $options2
     *
     * @return bool
     */
    public function compareOptions($options1, $options2)
    {
        if ($this->product->id != $options2['product_id']) {
            return false;
        } else {
            if (isset($options1['parent_id']) && isset($options2['parent_id'])) {
                if ($options1['parent_id'] == $options2['parent_id']) {
                    return true;
                } else {
                    return false;
                }
            } elseif (isset($options1['parent_id']) && !isset($options2['parent_id'])) {
                return false;
            } elseif (isset($options2['parent_id']) && !isset($options1['parent_id'])) {
                return false;
            }
        }

        return true;
    }

    /**
     * Returns additional information for items
     *
     * @param array $data
     *
     * @return array
     */
    public function getAdditionalOptions($data)
    {
        return $data;
    }

    /**
     * Get actual ordered item
     *
     * @param \Webkul\Checkout\Contracts\CartItem $item
     *
     * @return \Webkul\Checkout\Contracts\CartItem|\Webkul\Sales\Contracts\OrderItem|\Webkul\Sales\Contracts\InvoiceItem|\Webkul\Sales\Contracts\ShipmentItem|\Webkul\Customer\Contracts\Wishlist
     */
    public function getOrderedItem($item)
    {
        return $item;
    }

    /**
     * Get product base image
     *
     * @param \Webkul\Customer\Contracts\CartItem|\Webkul\Checkout\Contracts\CartItem $item
     *
     * @return array
     */
    public function getBaseImage($item)
    {
        return ProductImage::getProductBaseImage($item->product);
    }

    /**
     * Validate cart item product price and other things
     *
     * @param \Webkul\Checkout\Models\CartItem $item
     *
     * @return \Webkul\Product\Datatypes\CartItemValidationResult
     */
    public function validateCartItem(CartItem $item): CartItemValidationResult
    {
        $result = new CartItemValidationResult();

        if ($this->isCartItemInactive($item)) {
            $result->itemIsInactive();

            return $result;
        }

        $price = $item->product->getTypeInstance()->getFinalPrice($item->quantity);

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

    //get product options
    public function getProductOptions()
    {
        return $this->productOptions;
    }

    /**
     * Returns true, if cart item is inactive
     *
     * @param \Webkul\Checkout\Contracts\CartItem $item
     *
     * @return bool
     */
    public function isCartItemInactive(\Webkul\Checkout\Contracts\CartItem $item): bool
    {
        if ($item->product->status === 0) {
            return true;
        }

        switch ($item->product->type) {
            case 'bundle':
                foreach ($item->children as $child) {
                    if ($child->product->status === 0) {
                        return true;
                    }
                }
                break;

            case 'configurable':
                if ($item->child && $item->child->product->status === 0) {
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
    public function getCustomerGroupPricingOffers() {
        $offerLines = [];
        $haveOffers = true;
        $customerGroupId = null;

        if (Cart::getCurrentCustomer()->check()) {
            $customerGroupId = Cart::getCurrentCustomer()->user()->customer_group_id;
        } else {
            $customerGroupRepository = app('Webkul\Customer\Repositories\CustomerGroupRepository');

            if ($customerGuestGroup = $customerGroupRepository->findOneByField('code', 'guest')) {
                $customerGroupId = $customerGuestGroup->id;
            }
        }

        $customerGroupPrices = $this->product->customer_group_prices()->where(function ($query) use ($customerGroupId) {
            $query->where('customer_group_id', $customerGroupId)
                ->orWhereNull('customer_group_id');
        }
        )->groupBy('qty')->get()->sortBy('qty')->values()->all();

        if ($this->haveSpecialPrice()) {
            $rulePrice = app('Webkul\CatalogRule\Helpers\CatalogRuleProductPrice')->getRulePrice($this->product);

            if ($rulePrice && $rulePrice->price < $this->product->special_price) {
                $haveOffers = false;
            }

            if ($haveOffers) {
                foreach ($customerGroupPrices as $key => $customerGroupPrice) {
                    if ($customerGroupPrice && $customerGroupPrice->qty > 1) {
                        array_push($offerLines, $this->getOfferLines($customerGroupPrice));
                    }
                }
            }
        } else {
            if (count($customerGroupPrices) > 0) {
                foreach ($customerGroupPrices as $key => $customerGroupPrice) {
                    array_push($offerLines, $this->getOfferLines($customerGroupPrice));
                }
            }
        }

        return $offerLines;
    }

    /**
     * Get offers lines.
     *
     * @param array $customerGroupPrice
     *
     * @return array
     */
    public function getOfferLines($customerGroupPrice) {
        $price = $this->getCustomerGroupPrice($this->product, $customerGroupPrice->qty);

        $discount = number_format((($this->product->price - $price) * 100) / ($this->product->price), 2);

        $offerLines = trans('shop::app.products.offers', ['qty'  => $customerGroupPrice->qty,
            'price' =>  core()->currency($price), 'discount' => $discount]);

        return $offerLines;
    }
}