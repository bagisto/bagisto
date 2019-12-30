<?php

namespace Webkul\Velocity\Helpers;

use DB;
use Webkul\Product\Helpers\Review;
use Webkul\Product\Models\Product as ProductModel;
use Webkul\Velocity\Repositories\OrderBrandsRepository;
use Webkul\Velocity\Repositories\VelocityMetadataRepository;
use Webkul\Attribute\Repositories\AttributeOptionRepository;
use Webkul\Product\Repositories\ProductRepository as ProductRepository;

/**
 * Search controller
 *
 * @author  Shubham Mehrotra <shubhammehrotra.symfony@webkul.com> @shubhwebkul
 * @copyright 2019 Webkul Software Pvt Ltd (http://www.webkul.com)
 */

class Helper extends Review
{

    /**
     * orderBrands object
     *
     * @var object
     */
    protected $orderBrands;

    /**
     * productRepository object
     *
     * @var object
     */
    protected $productRepository;

     /**
     * productModel object
     *
     * @var object
     */
    protected $productModel;

      /**
     * productModel object
     *
     * @var object
     */
    protected $attributeOption;

    /**
     * VelocityMetadata object
     *
     * @var object
     */
    protected $velocityMetadata;

    public function __construct(
        VelocityMetadataRepository $velocityMetadata,
        OrderBrandsRepository $orderBrandsRepository,
        ProductRepository $productRepository,
        ProductModel $productModel,
        AttributeOptionRepository $attributeOption
    ) {
        $this->velocityMetadata =  $velocityMetadata;
        $this->productModel =  $productModel;
        $this->attributeOption =  $attributeOption;
        $this->productRepository = $productRepository;
        $this->orderBrandsRepository = $orderBrandsRepository;
    }

    public function topBrand($order)
    {
        $orderItems = $order->items;

        foreach ($orderItems as $key => $orderItem) {
            $products[] = $orderItem->product;

            $this->orderBrandsRepository->create([
                'order_id' => $orderItem->order_id,
                'order_item_id' => $orderItem->id,
                'product_id' => $orderItem->product_id,
                'brand' => $products[$key]->brand,
            ]);
        }
    }


    public function getBrandsWithCategories()
    {
        try {
            $orderBrand = $this->orderBrandsRepository->get()->toArray();

            if (isset($orderBrand) && ! empty($orderBrand)) {
                foreach ($orderBrand as $product) {
                    $product_id[] = $product['product_id'];

                    $product_categories = $this->productRepository->with('categories')->findWhereIn('id', $product_id)->toArray();
                }

                $categoryName = $brandName = $brandImplode = [];
                foreach($product_categories as $totalData) {
                    $brand = $this->attributeOption->findOneWhere(['id' => $totalData['brand']]);

                    foreach ($totalData['categories'] as $categories) {
                        foreach($categories['translations'] as $catName) {
                            $brandData[$brand->admin_name][] = $catName['name'];
                            $categoryName[] = $catName['name'];
                        }
                    }
                }

                $uniqueCategoryName = array_unique($categoryName);

                foreach($uniqueCategoryName as $key => $categoryNameValue) {
                    foreach($brandData as $brandDataKey => $brandDataValue) {
                        if(in_array($categoryNameValue,$brandDataValue)) {
                            $brandName[$categoryNameValue][] = $brandDataKey;
                        }
                    }
                }

                foreach($brandName as $brandKey => $brandvalue) {
                    $brandImplode[$brandKey][] = implode(' | ',array_map("ucfirst", $brandvalue));
                }

                return $brandImplode;
            }
        } catch (Exception $exception){
            throw $exception;
        }
    }

    public function arrangeWithMaxBrandOrder($orderBrandData)
    {
    }

    /**
     * Returns the count rating of the product
     *
    * @param Product $product
     * @return array
     */
    public function getCountRating($product)
    {
        $reviews = $product->reviews()->where('status', 'approved')
            ->select('rating', DB::raw('count(*) as total'))
            ->groupBy('rating')
            ->orderBy('rating','desc')
            ->get();

        $totalReviews = $this->getTotalReviews($product);

        for ($i = 5; $i >= 1; $i--) {
            if (! $reviews->isEmpty()) {
                foreach ($reviews as $review) {
                    if ($review->rating == $i) {
                        $percentage[$i] = $review->total;

                        break;
                    } else {
                        $percentage[$i]=0;
                    }
                }
            } else {
                $percentage[$i]=0;
            }
        }

        return $percentage;
    }

    public function getVelocityMetaData()
    {
        $metaData = $this->velocityMetadata->get();

        if (! ($metaData && ($metaData = $metaData[0]))) {
            $metaData = null;
        }

        return $metaData;
    }
}

