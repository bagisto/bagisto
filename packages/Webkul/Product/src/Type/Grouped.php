<?php

namespace Webkul\Product\Type;

use Webkul\Attribute\Repositories\AttributeRepository;
use Webkul\Product\Repositories\ProductRepository;
use Webkul\Product\Repositories\ProductAttributeValueRepository;
use Webkul\Product\Repositories\ProductInventoryRepository;
use Webkul\Product\Repositories\ProductImageRepository;
use Webkul\Product\Repositories\ProductGroupedProductRepository;
use Webkul\Product\Helpers\ProductImage;
use Webkul\Product\Models\ProductAttributeValue;
use Webkul\Product\Models\ProductFlat;

/**
 * Class Grouped.
 *
 * @author    Jitendra Singh <jitendra@webkul.com>
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class Grouped extends AbstractType
{
    /**
     * ProductGroupedProductRepository instance
     *
     * @var ProductGroupedProductRepository
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
        'admin::catalog.products.accordians.product-links'
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
     * @param  Webkul\Attribute\Repositories\AttributeRepository           $attributeRepository
     * @param  Webkul\Product\Repositories\ProductRepository               $productRepository
     * @param  Webkul\Product\Repositories\ProductAttributeValueRepository $attributeValueRepository
     * @param  Webkul\Product\Repositories\ProductInventoryRepository      $productInventoryRepository
     * @param  Webkul\Product\Repositories\ProductImageRepository          $productImageRepository
     * @param  Webkul\Product\Repositories\ProductGroupedProductRepository $productGroupedProductRepository
     * @param  Webkul\Product\Helpers\ProductImage                         $productImageHelper
     * @return void
     */
    public function __construct(
        AttributeRepository $attributeRepository,
        ProductRepository $productRepository,
        ProductAttributeValueRepository $attributeValueRepository,
        ProductInventoryRepository $productInventoryRepository,
        ProductImageRepository $productImageRepository,
        ProductGroupedProductRepository $productGroupedProductRepository,
        ProductImage $productImageHelper
    )
    {
        parent::__construct(
            $attributeRepository,
            $productRepository,
            $attributeValueRepository,
            $productInventoryRepository,
            $productImageRepository,
            $productImageHelper
        );

        $this->productGroupedProductRepository = $productGroupedProductRepository;
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

        if (request()->route()->getName() != 'admin.catalog.products.massupdate')
            $this->productGroupedProductRepository->saveGroupedProducts($data, $product);

        return $product;
    }

    /**
     * Get product minimal price
     *
     * @return float
     */
    public function getMinimalPrice()
    {
        $minPrices = [];

        $result = $this->product->grouped_products()
            ->join('product_flat', 'product_grouped_products.associated_product_id', '=', 'product_flat.product_id')
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
     * Get product minimal price
     *
     * @return string
     */
    public function getPriceHtml()
    {
        return '<span class="price-label">' . trans('shop::app.products.starting-at') . '</span>'
            . '<span class="final-price">' . core()->currency($this->getMinimalPrice()) . '</span>';
    }

    /**
     * Add product. Returns error message if can't prepare product.
     *
     * @param array $data
     * @return array
     */
    public function prepareForCart($data)
    {
        if (! isset($data['qty']) || ! is_array($data['qty']))
            return trans('shop::app.checkout.cart.integrity.missing_options');

        $products = [];

        foreach ($data['qty'] as $productId => $qty) {
            if (! $qty)
                continue;

            $product = $this->productRepository->find($productId);

            $cartProducts = $product->getTypeInstance()->prepareForCart([
                    'product_id' => $productId,
                    'quantity' => $qty,
                ]);

            if (is_string($cartProducts))
                return $cartProducts;
                
            $products = array_merge($products, $cartProducts);
        }

        if (! count($products))
            return trans('shop::app.checkout.cart.integrity.qty_missing');

        return $products;
    }
}