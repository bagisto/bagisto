<?php

namespace Webkul\Product\Type;

use Webkul\Attribute\Repositories\AttributeRepository;
use Webkul\Product\Repositories\ProductRepository;
use Webkul\Product\Repositories\ProductAttributeValueRepository;
use Webkul\Product\Repositories\ProductInventoryRepository;
use Webkul\Product\Repositories\ProductImageRepository;
use Webkul\Product\Repositories\ProductVideoRepository;
use Webkul\Product\Repositories\ProductGroupedProductRepository;
use Webkul\Product\Models\ProductAttributeValue;
use Webkul\Product\Models\ProductFlat;

class Grouped extends AbstractType
{
    /**
     * ProductGroupedProductRepository instance
     *
     * @var \Webkul\Product\Repositories\ProductGroupedProductRepository
     */
    protected $productGroupedProductRepository;

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
        'admin::catalog.products.accordians.grouped-products',
        'admin::catalog.products.accordians.channels',
        'admin::catalog.products.accordians.product-links',
        'admin::catalog.products.accordians.videos',
    ];

    /**
     * Is a composite product type
     *
     * @var boolean
     */
    protected $isComposite = true;

    /**
     * Create a new product type instance.
     *
     * @param  \Webkul\Attribute\Repositories\AttributeRepository            $attributeRepository
     * @param  \Webkul\Product\Repositories\ProductRepository                $productRepository
     * @param  \Webkul\Product\Repositories\ProductAttributeValueRepository  $attributeValueRepository
     * @param  \Webkul\Product\Repositories\ProductInventoryRepository       $productInventoryRepository
     * @param  \Webkul\Product\Repositories\ProductImageRepository           $productImageRepository
     * @param  \Webkul\Product\Repositories\ProductGroupedProductRepository  $productGroupedProductRepository
     * @param  \Webkul\Product\Repositories\ProductVideoRepository           $productVideoRepository
     * @return void
     */
    public function __construct(
        AttributeRepository $attributeRepository,
        ProductRepository $productRepository,
        ProductAttributeValueRepository $attributeValueRepository,
        ProductInventoryRepository $productInventoryRepository,
        ProductImageRepository $productImageRepository,
        ProductGroupedProductRepository $productGroupedProductRepository,
        ProductVideoRepository $productVideoRepository
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

        $this->productGroupedProductRepository = $productGroupedProductRepository;
    }

    /**
     * @param  array  $data
     * @param  int  $id
     * @param  string  $attribute
     * @return \Webkul\Product\Contracts\Product
     */
    public function update(array $data, $id, $attribute = "id")
    {
        $product = parent::update($data, $id, $attribute);
        $route = request()->route() ? request()->route()->getName() : '';

        if ($route != 'admin.catalog.products.massupdate') {
            $this->productGroupedProductRepository->saveGroupedProducts($data, $product);
        }

        return $product;
    }

    /**
     * Returns children ids
     *
     * @return array
     */
    public function getChildrenIds()
    {
        return array_unique($this->product->grouped_products()->pluck('associated_product_id')->toArray());
    }

    /**
     * Check if catalog rule can be applied
     *
     * @return bool
     */
    public function priceRuleCanBeApplied()
    {
        return false;
    }

    /**
     * Get product minimal price
     *
     * @return float
     */
    public function getMinimalPrice($qty = null)
    {
        $minPrices = [];

        foreach ($this->product->grouped_products as $groupOptionProduct) {
            $minPrices[] = $groupOptionProduct->associated_product->getTypeInstance()->getMinimalPrice();
        }

        if (empty($minPrices)) {
            return 0;
        }

        return min($minPrices);
    }

    /**
     * @return bool
     */
    public function isSaleable()
    {
        if (!$this->product->status) {
            return false;
        }

        if (ProductFlat::query()->select('id')->whereIn('product_id', $this->getChildrenIds())->where('status', 0)->first()) {
            return false;
        }

        return true;
    }

    /**
     * Get group product special price
     *
     * @return boolean
     */
    private function checkGroupProductHaveSpecialPrice()
    {
        $haveSpecialPrice = false;
        foreach ($this->product->grouped_products as $groupOptionProduct) {
            if ($groupOptionProduct->associated_product->getTypeInstance()->haveSpecialPrice()) {
                $haveSpecialPrice = true;
                break;
            }
        }
        return $haveSpecialPrice;
    }

    /**
     * Get product minimal price
     *
     * @return string
     */
    public function getPriceHtml()
    {
        $html = '';

        if ($this->checkGroupProductHaveSpecialPrice())
            $html .= '<div class="sticker sale">' . trans('shop::app.products.sale') . '</div>';

        $html .= '<span class="price-label">' . trans('shop::app.products.starting-at') . '</span>'
        . ' '
        . '<span class="final-price">' . core()->currency($this->getMinimalPrice()) . '</span>';

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
        if (! isset($data['qty']) || ! is_array($data['qty'])) {
            return trans('shop::app.checkout.cart.integrity.missing_options');
        }

        $products = [];

        foreach ($data['qty'] as $productId => $qty) {
            if (! $qty) {
                continue;
            }

            $product = $this->productRepository->find($productId);

            $cartProducts = $product->getTypeInstance()->prepareForCart([
                'product_id' => $productId,
                'quantity'   => $qty,
            ]);

            if (is_string($cartProducts)) {
                return $cartProducts;
            }

            $products = array_merge($products, $cartProducts);
        }

        if (! count($products)) {
            return trans('shop::app.checkout.cart.integrity.qty_missing');
        }

        return $products;
    }
}
