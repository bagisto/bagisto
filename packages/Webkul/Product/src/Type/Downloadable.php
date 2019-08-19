<?php

namespace Webkul\Product\Type;

use Webkul\Attribute\Repositories\AttributeRepository;
use Webkul\Product\Repositories\ProductRepository;
use Webkul\Product\Repositories\ProductAttributeValueRepository;
use Webkul\Product\Repositories\ProductInventoryRepository;
use Webkul\Product\Repositories\ProductImageRepository;
use Webkul\Product\Repositories\ProductDownloadableLinkRepository;
use Webkul\Product\Repositories\ProductDownloadableSampleRepository;
use Webkul\Product\Models\ProductAttributeValue;
use Webkul\Product\Helpers\Price;
use Webkul\Product\Helpers\ProductImage;
use Webkul\Checkout\Models\CartItem;

/**
 * Class Downloadable.
 *
 * @author    Jitendra Singh <jitendra@webkul.com>
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class Downloadable extends AbstractType
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
     * ProductDownloadableLinkRepository instance
     * 
     * @var ProductDownloadableLinkRepository
    */
    protected $productDownloadableLinkRepository;

    /**
     * ProductDownloadableSampleRepository instance
     * 
     * @var ProductDownloadableSampleRepository
    */
    protected $productDownloadableSampleRepository;

    /**
     * Product price helper instance
     * 
     * @var Price
    */
    protected $priceHelper;

    /**
     * Product Image helper instance
     * 
     * @var ProductImage
    */
    protected $productImageHelper;

    /**
     * Skip attribute for downloadable product type
     *
     * @var array
     */
    protected $skipAttributes = ['width', 'height', 'depth', 'weight'];

    /**
     * These blade files will be included in product edit page
     * 
     * @var array
     */
    protected $additionalViews = [
        'admin::catalog.products.accordians.images',
        'admin::catalog.products.accordians.categories',
        'admin::catalog.products.accordians.downloadable',
        'admin::catalog.products.accordians.product-links'
    ];

    /**
     * Create a new product type instance.
     *
     * @param  Webkul\Attribute\Repositories\AttributeRepository               $attributeRepository
     * @param  Webkul\Product\Repositories\ProductRepository                   $productRepository
     * @param  Webkul\Product\Repositories\ProductAttributeValueRepository     $attributeValueRepository
     * @param  Webkul\Product\Repositories\ProductInventoryRepository          $productInventoryRepository
     * @param  Webkul\Product\Repositories\ProductImageRepository              $productImageRepository
     * @param  Webkul\Product\Repositories\ProductDownloadableLinkRepository   $productDownloadableLinkRepository
     * @param  Webkul\Product\Repositories\ProductDownloadableSampleRepository $productDownloadableSampleRepository
     * @param  Webkul\Product\Helpers\Price                                    $priceHelper
     * @param  Webkul\Product\Helpers\ProductImage                             $productImageHelper
     * @return void
     */
    public function __construct(
        AttributeRepository $attributeRepository,
        ProductRepository $productRepository,
        ProductAttributeValueRepository $attributeValueRepository,
        ProductInventoryRepository $productInventoryRepository,
        productImageRepository $productImageRepository,
        ProductDownloadableLinkRepository $productDownloadableLinkRepository,
        ProductDownloadableSampleRepository $productDownloadableSampleRepository,
        Price $priceHelper,
        ProductImage $productImageHelper
    )
    {
        parent::__construct(
            $attributeRepository,
            $productRepository,
            $attributeValueRepository,
            $productInventoryRepository,
            $productImageRepository,
            $priceHelper,
            $productImageHelper
        );

        $this->productDownloadableLinkRepository = $productDownloadableLinkRepository;

        $this->productDownloadableSampleRepository = $productDownloadableSampleRepository;
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

        if (request()->route()->getName() != 'admin.catalog.products.massupdate') {
            $this->productDownloadableLinkRepository->saveLinks($data, $product);
            
            $this->productDownloadableSampleRepository->saveSamples($data, $product);
        }

        return $product;
    }

    /**
     * Return true if this product type is saleable
     *
     * @return boolean
     */
    public function isSaleable()
    {
        if (! $this->product->status)
            return false;
        
        if ($this->product->downloadable_links()->count())
            return true;

        return false;            
    }

    /**
     * Returns validation rules
     *
     * @return array
     */
    public function getTypeValidationRules()
    {
        return [
            // 'downloadable_links.*.title' => 'required',
            'downloadable_links.*.type' => 'required',
            'downloadable_links.*.file' => 'required_if:type,==,file',
            'downloadable_links.*.file_name' => 'required_if:type,==,file',
            'downloadable_links.*.url' => 'required_if:type,==,url',
            'downloadable_links.*.downloads' => 'required',
            'downloadable_links.*.sort_order' => 'required',
        ];
    }

    /**
     * Add product. Returns error message if can't prepare product.
     *
     * @param array   $data
     * @return array
     */
    public function prepareForCart($data)
    {
        if(! isset($data['links']) || ! count($data['links']))
            return trans('shop::app.checkout.cart.integrity.missing_links');

        return parent::prepareForCart($data);
    }
    
    /**
     * Check if product can be configured
     * 
     * @return boolean
     */
    public function canConfigure()
    {
        return true;
    }
    
    /**
     *
     * @param array $options1
     * @param array $options2
     * @return boolean
     */
    public function compareOptions($options1, $options2)
    {
        if ($this->product->id != $options2['product_id'])
            return false;

        return $options1['links'] == $options2['links'];
    }

    /**
     * Returns additional information for items
     *
     * @param array $data
     * @return array
     */
    public function getAdditionalOptions($data)
    {
        $labels = [];

        foreach ($this->product->downloadable_links as $link) {
            if (in_array($link->id, $data['links']))
                $labels[] = $link->title;
        }

        $data['attributes'][0] = [
            'attribute_name' => 'Downloads',
            'option_id' => 0,
            'option_label' => implode(', ', $labels),
        ];

        return $data;
    }
}