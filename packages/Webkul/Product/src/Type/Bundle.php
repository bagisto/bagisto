<?php

namespace Webkul\Product\Type;

use Webkul\Attribute\Repositories\AttributeRepository;
use Webkul\Checkout\Models\CartItem;
use Webkul\Customer\Repositories\CustomerRepository;
use Webkul\Product\DataTypes\CartItemValidationResult;
use Webkul\Product\Helpers\BundleOption;
use Webkul\Product\Helpers\Indexers\Price\Bundle as BundleIndexer;
use Webkul\Product\Repositories\ProductAttributeValueRepository;
use Webkul\Product\Repositories\ProductBundleOptionProductRepository;
use Webkul\Product\Repositories\ProductBundleOptionRepository;
use Webkul\Product\Repositories\ProductCustomerGroupPriceRepository;
use Webkul\Product\Repositories\ProductImageRepository;
use Webkul\Product\Repositories\ProductInventoryRepository;
use Webkul\Product\Repositories\ProductRepository;
use Webkul\Product\Repositories\ProductVideoRepository;
use Webkul\Tax\Facades\Tax;

class Bundle extends AbstractType
{
    /**
     * Skip attribute for Bundle product type.
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
        'depth',
        'manage_stock',
    ];

    /**
     * Is a composite product type.
     *
     * @var bool
     */
    protected $isComposite = true;

    /**
     * Product children price can be calculated or not.
     *
     * @var bool
     */
    protected $isChildrenCalculated = true;

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
        protected ProductBundleOptionRepository $productBundleOptionRepository,
        protected ProductBundleOptionProductRepository $productBundleOptionProductRepository,
        protected BundleOption $bundleOptionHelper
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

        $this->productBundleOptionRepository->saveBundleOptions($data, $product);

        return $product;
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

        if (in_array('bundle_options', $attributesToSkip)) {
            return;
        }

        foreach ($this->product->bundle_options as $bundleOption) {
            $product->bundle_options()->save($bundleOption->replicate());
        }
    }

    /**
     * Returns children ids.
     *
     * @return array
     */
    public function getChildrenIds()
    {
        return array_unique($this->product->bundle_options()->pluck('product_id')->toArray());
    }

    /**
     * Check if catalog rule can be applied.
     *
     * @return bool
     */
    public function priceRuleCanBeApplied()
    {
        return false;
    }

    /**
     * Get product minimal price.
     *
     * @param  int  $qty
     * @return float
     */
    public function getFinalPrice($qty = null)
    {
        return round(0, 2);
    }

    /**
     * Returns product prices.
     *
     * @return array
     */
    public function getProductPrices()
    {
        return [
            'from' => [
                'regular' => [
                    'price'           => core()->convertPrice($regularMinimalPrice = $this->getRegularMinimalPrice()),
                    'formatted_price' => core()->currency($regularMinimalPrice),
                ],

                'final'   => [
                    'price'           => core()->convertPrice($minimalPrice = $this->getMinimalPrice()),
                    'formatted_price' => core()->currency($minimalPrice),
                ],
            ],

            'to' => [
                'regular' => [
                    'price'           => core()->convertPrice($regularMaximumPrice = $this->getRegularMaximumPrice()),
                    'formatted_price' => core()->currency($regularMaximumPrice),
                ],

                'final'   => [
                    'price'           => core()->convertPrice($maximumPrice = $this->getMaximumPrice()),
                    'formatted_price' => core()->currency($maximumPrice),
                ],
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
        return view('shop::products.prices.bundle', [
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
        $bundleQuantity = parent::handleQuantity((int) $data['quantity']);

        if (empty($data['bundle_options'])) {
            return trans('product::app.checkout.cart.missing-options');
        }

        $data['bundle_options'] = array_filter($this->validateBundleOptionForCart($data['bundle_options']));

        if (empty($data['bundle_options'])) {
            return trans('product::app.checkout.cart.missing-options');
        }

        if (! $this->haveSufficientQuantity($data['quantity'])) {
            return trans('product::app.checkout.cart.inventory-warning');
        }

        $products = parent::prepareForCart($data);

        foreach ($this->getCartChildProducts($data) as $productId => $data) {
            $product = $this->productRepository->find($productId);

            /* need to check each individual quantity as well if don't have then show error */
            if (! $product->getTypeInstance()->haveSufficientQuantity($data['quantity'] * $bundleQuantity)) {
                return trans('product::app.checkout.cart.inventory-warning');
            }

            if (! $product->getTypeInstance()->isSaleable()) {
                continue;
            }

            $cartProduct = $product->getTypeInstance()->prepareForCart(array_merge($data, [
                'parent_id' => $this->product->id,
            ]));

            if (is_string($cartProduct)) {
                return $cartProduct;
            }

            $cartProduct[0]['parent_id'] = $this->product->id;
            $cartProduct[0]['quantity'] = $data['quantity'];
            $cartProduct[0]['total_weight'] = $cartProduct[0]['weight'] * $data['quantity'];
            $cartProduct[0]['base_total_weight'] = $cartProduct[0]['weight'] * $data['quantity'];

            $products = array_merge($products, $cartProduct);

            $products[0]['price'] += $cartProduct[0]['total'];
            $products[0]['price_incl_tax'] += $cartProduct[0]['total'];
            $products[0]['base_price'] += $cartProduct[0]['base_total'];
            $products[0]['base_price_incl_tax'] += $cartProduct[0]['base_total'];
            $products[0]['total'] += $cartProduct[0]['total'];
            $products[0]['total_incl_tax'] += $cartProduct[0]['total'];
            $products[0]['base_total'] += $cartProduct[0]['base_total'];
            $products[0]['base_total_incl_tax'] += $cartProduct[0]['base_total'];
            $products[0]['weight'] += ($cartProduct[0]['weight'] * $products[0]['quantity']);
            $products[0]['total_weight'] += ($cartProduct[0]['total_weight'] * $products[0]['quantity']);
            $products[0]['base_total_weight'] += ($cartProduct[0]['base_total_weight'] * $products[0]['quantity']);
        }

        return $products;
    }

    /**
     * Add product. Returns error message if can't prepare product.
     *
     * @param  array  $data
     * @return array
     */
    public function getCartChildProducts($data)
    {
        $products = [];

        foreach ($data['bundle_options'] as $optionId => $optionProductIds) {
            foreach ($optionProductIds as $optionProductId) {
                if (! $optionProductId) {
                    continue;
                }

                $optionProduct = $this->productBundleOptionProductRepository->findOneWhere([
                    'id'                       => $optionProductId,
                    'product_bundle_option_id' => $optionId,
                ]);

                if (! $optionProduct?->product->getTypeInstance()->isSaleable()) {
                    continue;
                }

                $qty = $data['bundle_option_qty'][$optionId] ?? $optionProduct->qty;

                if (! isset($products[$optionProduct->product_id])) {
                    $products[$optionProduct->product_id] = [
                        'product_id' => $optionProduct->product_id,
                        'quantity'   => $qty,
                    ];
                } else {
                    $products[$optionProduct->product_id] = array_merge($products[$optionProduct->product_id], [
                        'quantity' => $products[$optionProduct->product_id]['quantity'] + $qty,
                    ]);
                }
            }
        }

        return $products;
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
            isset($options1['bundle_options'])
            && isset($options2['bundle_options'])
        ) {
            return $options1['bundle_options'] == $options2['bundle_options']
                && $options1['bundle_option_qty'] == $this->getOptionQuantities($options2);
        }

        return false;
    }

    /**
     * Remove invalid options from add to cart request.
     *
     * @param  array  $data
     * @return array
     */
    public function validateBundleOptionForCart($data)
    {
        foreach ($data as $key => $value) {
            if (is_array($value)) {
                $data[$key] = $this->validateBundleOptionForCart($value);
            } elseif ($value) {
                $data[$key] = (int) $value;
            } else {
                unset($data[$key]);
            }
        }

        return $data;
    }

    /**
     * Returns additional information for items.
     *
     * @param  array  $data
     * @return array
     */
    public function getAdditionalOptions($data)
    {
        $bundleOptionQuantities = $data['bundle_option_qty'] ?? [];

        $productBundleOptions = $this->productBundleOptionRepository
            ->whereIn('id', array_keys($data['bundle_options']))
            ->orderBy('sort_order')
            ->get();

        $data['attributes'] = [];

        foreach ($productBundleOptions as $option) {
            $labels = [];

            foreach ($data['bundle_options'][$option->id] as $optionProductId) {
                if (! $optionProductId) {
                    continue;
                }

                $optionProduct = $this->productBundleOptionProductRepository->find($optionProductId);

                $qty = $data['bundle_option_qty'][$option->id] ?? $optionProduct->qty;

                if (! isset($data['bundle_option_qty'][$option->id])) {
                    $bundleOptionQuantities[$option->id] = $qty;
                }

                $label = $qty.' x '.$optionProduct->product->name;

                $price = $optionProduct->product->getTypeInstance()->getMinimalPrice();

                if ($price != 0) {
                    $label .= ' '.core()->currency($price);
                }

                $labels[] = $label;
            }

            if (count($labels)) {
                $data['attributes'][] = [
                    'attribute_name' => $option->label,
                    'option_id'      => $option->id,
                    'option_label'   => implode(', ', $labels),
                ];
            }
        }

        $data['bundle_option_qty'] = $bundleOptionQuantities;

        return $data;
    }

    /**
     * Returns additional information for items.
     *
     * @param  array  $data
     * @return array
     */
    public function getOptionQuantities($data)
    {
        $optionQuantities = [];

        foreach ($data['bundle_options'] as $optionId => $optionProductIds) {
            foreach ($optionProductIds as $optionProductId) {
                if (! $optionProductId) {
                    continue;
                }

                if (isset($data['bundle_option_qty'][$optionId])) {
                    $optionQuantities[$optionId] = $data['bundle_option_qty'][$optionId];

                    continue;
                }

                $optionProduct = $this->productBundleOptionProductRepository->find($optionProductId);

                $optionQuantities[$optionId] = $optionProduct->qty;
            }
        }

        return $optionQuantities;
    }

    /**
     * Validate cart item product price and other things.
     */
    public function validateCartItem(CartItem $item): CartItemValidationResult
    {
        $validation = new CartItemValidationResult;

        if (parent::isCartItemInactive($item)) {
            $validation->itemIsInactive();

            return $validation;
        }

        $basePrice = 0;

        foreach ($item->children as $childItem) {
            $childValidation = $childItem->getTypeInstance()->validateCartItem($childItem);

            if ($childValidation->isItemInactive()) {
                $validation->itemIsInactive();
            }

            if ($childValidation->isCartInvalid()) {
                $validation->cartIsInvalid();
            }

            $basePrice += $childItem->base_price * $childItem->quantity;
        }

        $basePrice = round($basePrice, 4);

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

        $item->additional = $this->getAdditionalOptions($item->additional);

        $item->save();

        return $validation;
    }

    /**
     * Have sufficient quantity.
     */
    public function haveSufficientQuantity(int $qty): bool
    {
        // to consider a bundle in stock we need to check that at least one product from each required group is available for the given quantity
        foreach ($this->product->bundle_options as $option) {
            if ($option->is_required) {
                foreach ($option->bundle_option_products as $bundleOptionProduct) {
                    // as long as at least one product in the required group is available we can continue checking other groups
                    if ($bundleOptionProduct->product->haveSufficientQuantity($bundleOptionProduct->qty * $qty)) {
                        continue 2;
                    }
                }

                // if any required option does not have any in-stock product option we will get here.
                return false;
            }
        }

        return true;
    }

    /**
     * Returns price indexer class for a specific product type
     *
     * @return string
     */
    public function getPriceIndexer()
    {
        return app(BundleIndexer::class);
    }
}
