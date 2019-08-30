<?php

namespace Webkul\Product\Type;

use Webkul\Attribute\Repositories\AttributeRepository;
use Webkul\Product\Repositories\ProductRepository;
use Webkul\Product\Repositories\ProductAttributeValueRepository;
use Webkul\Product\Repositories\ProductInventoryRepository;
use Webkul\Product\Repositories\ProductImageRepository;
use Webkul\Product\Repositories\ProductBundleOptionRepository;
use Webkul\Product\Repositories\ProductBundleOptionProductRepository;
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
     * ProductBundleOptionProductRepository instance
     *
     * @var ProductBundleOptionProductRepository
     */
    protected $productBundleOptionProductRepository;

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
     * Is a composite product type
     *
     * @var boolean
     */
    protected $isComposite = true;

    /**
     * Create a new product type instance.
     *
     * @param  Webkul\Attribute\Repositories\AttributeRepository                $attributeRepository
     * @param  Webkul\Product\Repositories\ProductRepository                    $productRepository
     * @param  Webkul\Product\Repositories\ProductAttributeValueRepository      $attributeValueRepository
     * @param  Webkul\Product\Repositories\ProductInventoryRepository           $productInventoryRepository
     * @param  Webkul\Product\Repositories\ProductImageRepository               $productImageRepository
     * @param  Webkul\Product\Repositories\ProductBundleOptionRepository        $productBundleOptionRepository
     * @param  Webkul\Product\Repositories\ProductBundleOptionProductRepository $productBundleOptionProductRepository
     * @param  Webkul\Product\Helpers\ProductImage                              $productImageHelper
     * @return void
     */
    public function __construct(
        AttributeRepository $attributeRepository,
        ProductRepository $productRepository,
        ProductAttributeValueRepository $attributeValueRepository,
        ProductInventoryRepository $productInventoryRepository,
        productImageRepository $productImageRepository,
        ProductBundleOptionRepository $productBundleOptionRepository,
        ProductBundleOptionProductRepository $productBundleOptionProductRepository,
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

        $this->productBundleOptionProductRepository = $productBundleOptionProductRepository;
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

    /**
     * Get product minimal price
     *
     * @return float
     */
    public function getMinimalPrice()
    {
        return 0;
    }

    /**
     * Get product maximam price
     *
     * @return float
     */
    public function getMaximamPrice()
    {
        return 0;
    }

    /**
     * Get product final price
     *
     * @return float
     */
    public function getFinalPrice()
    {
        return 0;
    }

    /**
     * Get product minimal price
     *
     * @return string
     */
    public function getPriceHtml()
    {
    }

    /**
     * Add product. Returns error message if can't prepare product.
     *
     * @param array   $data
     * @return array
     */
    public function prepareForCart($data)
    {
        if (! isset($data['bundle_options']))
            return trans('shop::app.checkout.cart.item.error-add');
        
        $products = parent::prepareForCart($data);

        foreach ($this->getCartChildProducts($data) as $productId => $data) {
            $product = $this->productRepository->find($productId);

            $cartProduct = $product->getTypeInstance()->prepareForCart($data);

            if (is_string($cartProduct))
                return $cartProduct;

            $cartProduct[0]['parent_id'] = $this->product->id;
                
            $products = array_merge($products, $cartProduct);

            $products[0]['price'] += $cartProduct[0]['total'];
            $products[0]['base_price'] += $cartProduct[0]['base_total'];
            $products[0]['total'] += $cartProduct[0]['total'];
            $products[0]['base_total'] += $cartProduct[0]['base_total'];
            $products[0]['weight'] += $cartProduct[0]['weight'];
            $products[0]['total_weight'] += $cartProduct[0]['total_weight'];
            $products[0]['base_total_weight'] += $cartProduct[0]['base_total_weight'];
        }

        return $products;
    }

    /**
     * Add product. Returns error message if can't prepare product.
     *
     * @param array   $data
     * @return array
     */
    public function getCartChildProducts($data)
    {
        $products = [];

        foreach ($data['bundle_options'] as $optionId => $optionProductIds) {
            foreach ($optionProductIds as $optionProductId) {
                $optionProduct = $this->productBundleOptionProductRepository->findOneWhere([
                        'id' => $optionProductId,
                        'product_bundle_option_id' => $optionId
                    ]);

                $qty = $data['bundle_option_qty'][$optionId] ?? $optionProduct->qty;

                if (! isset($products[$optionProduct->product_id])) {
                    $products[$optionProduct->product_id] = [
                            'product_id' => $optionProduct->product_id,
                            'quantity' => $qty,
                        ];
                } else {
                    $products[$optionProduct->product_id] = array_merge($products[$optionProduct->product_id], [
                            'quantity' => $products[$optionProduct->product_id]['quantity'] + $qty
                        ]);
                }
            }
        }

        return $products;
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

        return $options1['bundle_options'] == $options2['bundle_options'];
    }

    /**
     * Returns additional information for items
     *
     * @param array $data
     * @return array
     */
    public function getAdditionalOptions($data)
    {
        foreach ($data['bundle_options'] as $optionId => $optionProductIds) {
            $option = $this->productBundleOptionRepository->find($optionId);

            $labels = [];

            foreach ($optionProductIds as $optionProductId) {
                $optionProduct = $this->productBundleOptionProductRepository->find($optionProductId);
                
                $labels[] = $optionProduct->product->name;
            }

            $data['attributes'][$option->id] = [
                'attribute_name' => $option->label,
                'option_id' => $option->id,
                'option_label' => implode(', ', $labels),
            ];
        }

        return $data;
    }
}