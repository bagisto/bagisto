<?php

namespace Webkul\Product\Type;

use Webkul\Attribute\Repositories\AttributeRepository;
use Webkul\Checkout\Models\CartItem;
use Webkul\Product\Datatypes\CartItemValidationResult;
use Webkul\Product\Repositories\ProductAttributeValueRepository;
use Webkul\Product\Repositories\ProductDownloadableLinkRepository;
use Webkul\Product\Repositories\ProductDownloadableSampleRepository;
use Webkul\Product\Repositories\ProductImageRepository;
use Webkul\Product\Repositories\ProductInventoryRepository;
use Webkul\Product\Repositories\ProductRepository;
use Webkul\Product\Repositories\ProductVideoRepository;

class Downloadable extends AbstractType
{
    /**
     * Skip attribute for downloadable product type.
     *
     * @var array
     */
    protected $skipAttributes = ['length', 'width', 'height', 'weight', 'depth', 'guest_checkout'];

    /**
     * These blade files will be included in product edit page.
     *
     * @var array
     */
    protected $additionalViews = [
        'admin::catalog.products.accordians.images',
        'admin::catalog.products.accordians.videos',
        'admin::catalog.products.accordians.categories',
        'admin::catalog.products.accordians.downloadable',
        'admin::catalog.products.accordians.channels',
        'admin::catalog.products.accordians.product-links',
    ];

    /**
     * Is a stokable product type.
     *
     * @var bool
     */
    protected $isStockable = false;

    /**
     * Show quantity box.
     *
     * @var bool
     */
    protected $allowMultipleQty = false;

    /**
     * Get product options.
     *
     * @var array
     */
    protected $getProductOptions = [];

    /**
     * Create a new product type instance.
     *
     * @param \Webkul\Attribute\Repositories\AttributeRepository  $attributeRepository
     * @param \Webkul\Product\Repositories\ProductRepository  $productRepository
     * @param \Webkul\Product\Repositories\ProductAttributeValueRepository  $attributeValueRepository
     * @param \Webkul\Product\Repositories\ProductInventoryRepository  $productInventoryRepository
     * @param \Webkul\Product\Repositories\ProductImageRepository  $productImageRepository
     * @param \Webkul\Product\Repositories\ProductDownloadableLinkRepository  $productDownloadableLinkRepository
     * @param \Webkul\Product\Repositories\ProductDownloadableSampleRepository  $productDownloadableSampleRepository
     * @param \Webkul\Product\Repositories\ProductVideoRepository  $productVideoRepository
     * @return void
     */
    public function __construct(
        AttributeRepository $attributeRepository,
        ProductRepository $productRepository,
        ProductAttributeValueRepository $attributeValueRepository,
        ProductInventoryRepository $productInventoryRepository,
        productImageRepository $productImageRepository,
        ProductVideoRepository $productVideoRepository,
        protected ProductDownloadableLinkRepository $productDownloadableLinkRepository,
        protected ProductDownloadableSampleRepository $productDownloadableSampleRepository
    )
    {
        parent::__construct(
            $attributeRepository,
            $productRepository,
            $attributeValueRepository,
            $productInventoryRepository,
            $productImageRepository,
            $productVideoRepository
        );
    }

    /**
     * Update.
     *
     * @param  array  $data
     * @param  int  $id
     * @param  string  $attribute
     * @return \Webkul\Product\Contracts\Product
     */
    public function update(array $data, $id, $attribute = 'id')
    {
        $product = parent::update($data, $id, $attribute);
        $route = request()->route() ? request()->route()->getName() : '';

        if ($route != 'admin.catalog.products.massupdate') {
            $this->productDownloadableLinkRepository->saveLinks($data, $product);

            $this->productDownloadableSampleRepository->saveSamples($data, $product);
        }

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

        if (
            is_callable(config('products.isSaleable'))
            && call_user_func(config('products.isSaleable'), $this->product) === false
        ) {
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
        if (
            ! isset($data['links'])
            || ! count($data['links'])
        ) {
            return trans('shop::app.checkout.cart.integrity.missing_links');
        }

        $products = parent::prepareForCart($data);

        foreach ($this->product->downloadable_links as $link) {
            if (! in_array($link->id, $data['links'])) {
                continue;
            }

            $products[0]['price'] += core()->convertPrice($link->price);
            $products[0]['base_price'] += $link->price;
            $products[0]['total'] += (core()->convertPrice($link->price) * $products[0]['quantity']);
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
     *
     * @param  \Webkul\Checkout\Models\CartItem  $item
     * @return \Webkul\Product\Datatypes\CartItemValidationResult
     */
    public function validateCartItem(CartItem $item): CartItemValidationResult
    {
        $result = new CartItemValidationResult();

        if (parent::isCartItemInactive($item)) {
            $result->itemIsInactive();

            return $result;
        }

        $price = $item->product->getTypeInstance()->getFinalPrice($item->quantity);

        foreach ($item->product->downloadable_links as $link) {
            if (! in_array($link->id, $item->additional['links'])) {
                continue;
            }

            $price += $link->price;
        }

        $price = round($price, 2);

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
     * Get product maximum price
     *
     * @return float
     */
    public function getMaximumPrice()
    {
        return $this->product->price;
    }
}
