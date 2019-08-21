<?php

namespace Webkul\Product\Type;

use Webkul\Attribute\Repositories\AttributeRepository;
use Webkul\Product\Repositories\ProductRepository;
use Webkul\Product\Repositories\ProductAttributeValueRepository;
use Webkul\Product\Repositories\ProductInventoryRepository;
use Webkul\Product\Repositories\ProductImageRepository;
use Webkul\Product\Repositories\ProductBundleOptionRepository;
use Webkul\Product\Helpers\ProductImage;

/**
 * Class Bundle.
 *
 * @author    Jitendra Singh <jitendra@webkul.com>
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class Bundle extends AbstractType
{
    /**
     * AttributeRepository instance
     *
     * @var AttributeRepository
     */
    protected $attributeRepository;

    /**
     * ProductRepository instance
     *
     * @var ProductRepository
     */
    protected $productRepository;

    /**
     * ProductAttributeValueRepository instance
     *
     * @var ProductAttributeValueRepository
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
     * @var ProductImageRepository
     */
    protected $productImageRepository;

    /**
     * ProductBundleOptionRepository instance
     *
     * @var ProductBundleOptionRepository
     */
    protected $productBundleOptionRepository;

    /**
     * Product Image helper instance
     * 
     * @var ProductImage
    */
    protected $productImageHelper;

    /**
     * Skip attribute for Bundle product type
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
        'admin::catalog.products.accordians.bundle-items',
        'admin::catalog.products.accordians.product-links'
    ];

    /**
     * Create a new product type instance.
     *
     * @param  Webkul\Attribute\Repositories\AttributeRepository           $attributeRepository
     * @param  Webkul\Product\Repositories\ProductRepository               $productRepository
     * @param  Webkul\Product\Repositories\ProductAttributeValueRepository $attributeValueRepository
     * @param  Webkul\Product\Repositories\ProductInventoryRepository      $productInventoryRepository
     * @param  Webkul\Product\Repositories\ProductImageRepository          $productImageRepository
     * @param  Webkul\Product\Repositories\ProductBundleOptionRepository   $productBundleOptionRepository
     * @param  Webkul\Product\Helpers\ProductImage                         $productImageHelper
     * @return void
     */
    public function __construct(
        AttributeRepository $attributeRepository,
        ProductRepository $productRepository,
        ProductAttributeValueRepository $attributeValueRepository,
        ProductInventoryRepository $productInventoryRepository,
        productImageRepository $productImageRepository,
        ProductBundleOptionRepository $productBundleOptionRepository,
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

        $this->productBundleOptionRepository = $productBundleOptionRepository;
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
            $this->productBundleOptionRepository->saveBundleOptons($data, $product);

        return $product;
    }
}