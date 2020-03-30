<?php

namespace Webkul\Product\Type;

use Illuminate\Support\Facades\Storage;
use phpDocumentor\Reflection\Types\Boolean;
use Webkul\Attribute\Repositories\AttributeRepository;
use Webkul\Product\Repositories\ProductRepository;
use Webkul\Product\Repositories\ProductAttributeValueRepository;
use Webkul\Product\Repositories\ProductInventoryRepository;
use Webkul\Product\Repositories\ProductImageRepository;
use Webkul\Product\Models\ProductAttributeValue;
use Webkul\Product\Helpers\ProductImage;
use Cart;

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
     * @var \Webkul\Product\Repositories\ProductInventoryRepository
     */
    protected $productImageRepository;

    /**
     * Product Image helper instance
     *
     * @var \Webkul\Product\Helpers\ProductImage
    */
    protected $productImageHelper;

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
     * Create a new product type instance.
     *
     * @param  \Webkul\Attribute\Repositories\AttributeRepository  $attributeRepository
     * @param  \Webkul\Product\Repositories\ProductRepository  $productRepository
     * @param  \Webkul\Product\Repositories\ProductAttributeValueRepository  $attributeValueRepository
     * @param  \Webkul\Product\Repositories\ProductInventoryRepository  $productInventoryRepository
     * @param  \Webkul\Product\Repositories\ProductImageRepository  $productImageRepository
     * @param  \Webkul\Product\Helpers\ProductImage  $productImageHelper
     * @return void
     */
    public function __construct(
        AttributeRepository $attributeRepository,
        ProductRepository $productRepository,
        ProductAttributeValueRepository $attributeValueRepository,
        ProductInventoryRepository $productInventoryRepository,
        ProductImageRepository $productImageRepository,
        ProductImage $productImageHelper
    )
    {
        $this->attributeRepository = $attributeRepository;

        $this->productRepository = $productRepository;

        $this->attributeValueRepository = $attributeValueRepository;

        $this->productInventoryRepository = $productInventoryRepository;

        $this->productImageRepository = $productImageRepository;

        $this->productImageHelper = $productImageHelper;
    }

    /**
     * @param  array  $data
     * @return \Webkul\Product\Contracts\Product
     */
    public function create(array $data)
    {
        return $this->productRepository->getModel()->create($data);
    }

    /**
     * @param  array  $data
     * @param  int  $id
     * @param  string  $attribute
     * @return \Webkul\Product\Contracts\Product
     */
    public function update(array $data, $id, $attribute = "id")
    {
        $product = $this->productRepository->find($id);

        $product->update($data);

        foreach ($product->attribute_family->custom_attributes as $attribute) {
            $route = request()->route() ? request()->route()->getName() : "";

            if ($attribute->type == 'boolean' && $route != 'admin.catalog.products.massupdate') {
                $data[$attribute->code] = isset($data[$attribute->code]) && $data[$attribute->code] ? 1 : 0;
            }

            if (! isset($data[$attribute->code])) {
                continue;
            }

            if ($attribute->type == 'price' && isset($data[$attribute->code]) && $data[$attribute->code] == '') {
                $data[$attribute->code] = null;
            }

            if ($attribute->type == 'date' && $data[$attribute->code] == '' && $route != 'admin.catalog.products.massupdate') {
                $data[$attribute->code] = null;
            }

            if ($attribute->type == 'multiselect' || $attribute->type == 'checkbox') {
                $data[$attribute->code] = implode(",", $data[$attribute->code]);
            }

            if ($attribute->type == 'image' || $attribute->type == 'file') {
                $data[$attribute->code] = gettype($data[$attribute->code]) == 'object'
                        ? request()->file($attribute->code)->store('product/' . $product->id)
                        : NULL;
            }

            $attributeValue = $this->attributeValueRepository->findOneWhere([
                'product_id'   => $product->id,
                'attribute_id' => $attribute->id,
                'channel'      => $attribute->value_per_channel ? $data['channel'] : null,
                'locale'       => $attribute->value_per_locale ? $data['locale'] : null,
            ]);

            if (! $attributeValue) {
                $this->attributeValueRepository->create([
                    'product_id'   => $product->id,
                    'attribute_id' => $attribute->id,
                    'value'        => $data[$attribute->code],
                    'channel'      => $attribute->value_per_channel ? $data['channel'] : null,
                    'locale'       => $attribute->value_per_locale ? $data['locale'] : null,
                ]);
            } else {
                $this->attributeValueRepository->update([
                    ProductAttributeValue::$attributeTypeFields[$attribute->type] => $data[$attribute->code]
                    ], $attributeValue->id
                );

                if ($attribute->type == 'image' || $attribute->type == 'file') {
                    Storage::delete($attributeValue->text_value);
                }
            }
        }

        $route = request()->route() ? request()->route()->getName() : "";

        if ($route != 'admin.catalog.products.massupdate') {
            if  (! isset($data['categories'])) {
                $data['categories'] = [];
            }

            $product->categories()->sync($data['categories']);

            $product->up_sells()->sync($data['up_sell'] ?? []);

            $product->cross_sells()->sync($data['cross_sell'] ?? []);

            $product->related_products()->sync($data['related_products'] ?? []);

            $this->productInventoryRepository->saveInventories($data, $product);

            $this->productImageRepository->uploadImages($data, $product);
        }

        return $product;
    }

    /**
     * Specify type instance product
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
        if (! $this->product->status) {
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
     * @param  int  $qty
     * @return bool
     */
    public function haveSufficientQuantity($qty)
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
     * @param  \Webkul\Checkout\Contracts\CartItem  $cartItem
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
     * @param  \Webkul\Checkout\Contracts\CartItem  $item
     * @return bool
     */
    public function canBeMovedFromWishlistToCart($item)
    {
        return $this->canBeMovedFromWishlistToCart;
    }

    /**
     * Retrieve product attributes
     *
     * @param  \Webkul\Attribute\Contracts\Group  $group
     * @param  bool  $skipSuperAttribute
     * @return \Illuminate\Support\Collection
     */
    public function getEditableAttributes($group = null, $skipSuperAttribute = true)
    {
        if ($skipSuperAttribute) {
            $this->skipAttributes = array_merge($this->product->super_attributes->pluck('code')->toArray(), $this->skipAttributes);
        }

        if (! $group) {
            return $this->product->attribute_family->custom_attributes()->whereNotIn('attributes.code', $this->skipAttributes)->get();
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
     * @return float
     */
    public function getMinimalPrice()
    {
        if ($this->haveSpecialPrice()) {
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
     * @return float
     */
    public function getFinalPrice()
    {
        return $this->getMinimalPrice();
    }

    /**
     * Returns the product's minimal price
     *
     * @return float
     */
    public function getSpecialPrice()
    {
        return $this->haveSpecialPrice() ? $this->product->special_price : $this->product->price;
    }

    /**
     * @return bool
     */
    public function haveSpecialPrice()
    {
        $rulePrice = app('Webkul\CatalogRule\Helpers\CatalogRuleProductPrice')->getRulePrice($this->product);

        if ((is_null($this->product->special_price) || ! (float) $this->product->special_price) && ! $rulePrice) {
            return false;
        }

        if (! (float) $this->product->special_price) {
            if ($rulePrice && $rulePrice->price < $this->product->price) {
                $this->product->special_price = $rulePrice->price;

                return true;
            }
        } else {
            if ($rulePrice && $rulePrice->price <= $this->product->special_price) {
                $this->product->special_price = $rulePrice->price;

                return true;
            } else {
                if (core()->isChannelDateInInterval($this->product->special_price_from, $this->product->special_price_to)) {
                    return true;
                } elseif ($rulePrice) {
                    $this->product->special_price = $rulePrice->price;

                    return true;
                }
            }
        }

        return false;
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
            ]
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
     * @param  array  $data
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
            ]
        ];

        return $products;
    }

    /**
     * Get request quantity
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
            if (isset($options1['parent_id']) && isset($options2['parent_id'])) {
                if ($options1['parent_id'] == $options2['parent_id']) {
                    return true;
                } else {
                    return false;
                }
            } elseif (isset($options1['parent_id']) && ! isset($options2['parent_id'])) {
                return false;
            } elseif (isset($options2['parent_id']) && ! isset($options1['parent_id'])) {
                return false;
            }
        }

        return true;
    }

    /**
     * Returns additional information for items
     *
     * @param  array  $data
     * @return array
     */
    public function getAdditionalOptions($data)
    {
        return $data;
    }

    /**
     * Get actual ordered item
     *
     * @param  \Webkul\Checkout\Contracts\CartItem $item
     * @return \Webkul\Checkout\Contracts\CartItem|\Webkul\Sales\Contracts\OrderItem|\Webkul\Sales\Contracts\InvoiceItem|\Webkul\Sales\Contracts\ShipmentItem|\Webkul\Customer\Contracts\Wishlist
     */
    public function getOrderedItem($item)
    {
        return $item;
    }

    /**
     * Get product base image
     *
     * @param  \Webkul\Customer\Contracts\CartItem|\Webkul\Checkout\Contracts\CartItem  $item
     * @return array
     */
    public function getBaseImage($item)
    {
        return $this->productImageHelper->getProductBaseImage($item->product);
    }

    /**
     * Validate cart item product price
     *
     * @param  \Webkul\Customer\Contracts\CartItem  $item
     * @return void
     */
    public function validateCartItem($item)
    {
        $price = $item->product->getTypeInstance()->getFinalPrice();

        if ($price == $item->base_price) {
            return;
        }

        $item->base_price = $price;
        $item->price = core()->convertPrice($price);

        $item->base_total = $price * $item->quantity;
        $item->total = core()->convertPrice($price * $item->quantity);

        $item->save();
    }
}