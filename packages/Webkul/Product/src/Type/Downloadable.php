<?php

namespace Webkul\Product\Type;

use Webkul\Attribute\Repositories\AttributeRepository;
use Webkul\Checkout\Models\CartItem;
use Webkul\Customer\Repositories\CustomerRepository;
use Webkul\Product\DataTypes\CartItemValidationResult;
use Webkul\Product\Helpers\Indexers\Price\Downloadable as DownloadableIndexer;
use Webkul\Product\Repositories\ProductAttributeValueRepository;
use Webkul\Product\Repositories\ProductCustomerGroupPriceRepository;
use Webkul\Product\Repositories\ProductDownloadableLinkRepository;
use Webkul\Product\Repositories\ProductDownloadableSampleRepository;
use Webkul\Product\Repositories\ProductImageRepository;
use Webkul\Product\Repositories\ProductInventoryRepository;
use Webkul\Product\Repositories\ProductRepository;
use Webkul\Product\Repositories\ProductVideoRepository;
use Webkul\Tax\Facades\Tax;

class Downloadable extends AbstractType
{
    /**
     * Skip attribute for downloadable product type.
     *
     * @var array
     */
    protected $skipAttributes = [
        'length',
        'width',
        'height',
        'weight',
        'depth',
        'manage_stock',
        'guest_checkout',
    ];

    /**
     * Is a stockable product type.
     *
     * @var bool
     */
    protected $isStockable = false;

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
        productImageRepository $productImageRepository,
        ProductVideoRepository $productVideoRepository,
        ProductCustomerGroupPriceRepository $productCustomerGroupPriceRepository,
        protected ProductDownloadableLinkRepository $productDownloadableLinkRepository,
        protected ProductDownloadableSampleRepository $productDownloadableSampleRepository
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

        $this->productDownloadableLinkRepository->saveLinks($data, $product);

        $this->productDownloadableSampleRepository->saveSamples($data, $product);

        return $product;
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

        if ($this->product->downloadable_links()->count()) {
            return true;
        }

        return false;
    }

    /**
     * Returns validation rules.
     *
     * @return array
     */
    public function getTypeValidationRules()
    {
        return [
            'downloadable_links.*.type'       => 'required',
            'downloadable_links.*.file'       => 'required_if:type,==,file',
            'downloadable_links.*.file_name'  => 'required_if:type,==,file',
            'downloadable_links.*.url'        => 'required_if:type,==,url',
            'downloadable_links.*.downloads'  => 'required',
            'downloadable_links.*.sort_order' => 'required',
        ];
    }

    /**
     * Add product. Returns error message if can't prepare product.
     *
     * @param  array  $data
     * @return array
     */
    public function prepareForCart($data)
    {
        if (empty($data['links'])) {
            return trans('product::app.checkout.cart.missing-links');
        }

        $products = parent::prepareForCart($data);

        foreach ($this->product->downloadable_links as $link) {
            if (! in_array($link->id, $data['links'])) {
                continue;
            }

            $products[0]['price'] += ($price = core()->convertPrice($link->price));
            $products[0]['price_incl_tax'] += $price;
            $products[0]['base_price'] += $link->price;
            $products[0]['base_price_incl_tax'] += $link->price;
            $products[0]['total'] += ($total = core()->convertPrice($link->price) * $products[0]['quantity']);
            $products[0]['total_incl_tax'] += $total;
            $products[0]['base_total'] += ($link->price * $products[0]['quantity']);
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
        if ($this->product->id != $options2['product_id']) {
            return false;
        }

        if (
            isset($options1['links'])
            && isset($options2['links'])
        ) {
            return $options1['links'] === $options2['links'];
        }

        if (! isset($options1['links'])) {
            return false;
        }

        if (! isset($options2['links'])) {
            return false;
        }
    }

    /**
     * Returns additional information for items.
     *
     * @param  array  $data
     * @return array
     */
    public function getAdditionalOptions($data)
    {
        $labels = [];

        foreach ($this->product->downloadable_links as $link) {
            if (in_array($link->id, $data['links'])) {
                $labels[] = $link->title;
            }
        }

        $data['attributes'][0] = [
            'attribute_name' => 'Downloads',
            'option_id'      => 0,
            'option_label'   => implode(', ', $labels),
        ];

        return $data;
    }

    /**
     * Validate cart item product price
     */
    public function validateCartItem(CartItem $item): CartItemValidationResult
    {
        $validation = new CartItemValidationResult;

        if (parent::isCartItemInactive($item)) {
            $validation->itemIsInactive();

            return $validation;
        }

        $basePrice = $this->getFinalPrice($item->quantity);

        foreach ($item->product->downloadable_links as $link) {
            if (! in_array($link->id, $item->additional['links'])) {
                continue;
            }

            $basePrice += $link->price;
        }

        $basePrice = round($basePrice, 2);

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
     * Get product maximum price
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
        return app(DownloadableIndexer::class);
    }
}
