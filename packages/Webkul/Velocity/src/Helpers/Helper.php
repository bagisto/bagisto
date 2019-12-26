<?php

namespace Webkul\Velocity\Helpers;

use Webkul\Product\Helpers\Review;
use Webkul\Product\Models\Product as ProductModel;
use Webkul\Velocity\Repositories\OrderBrandsRepository;
use Webkul\Attribute\Repositories\AttributeOptionRepository;
use Webkul\Product\Repositories\ProductRepository as ProductRepository;

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
    protected $AttributeOption;

    public function __construct(
        OrderBrandsRepository $OrderBrandsRepository,
        ProductRepository $productRepository,
        ProductModel $productModel,
        AttributeOptionRepository $AttributeOption
    ) {
        $this->OrderBrandsRepository = $OrderBrandsRepository;

        $this->productRepository = $productRepository;

        $this->productModel =  $productModel;

        $this->AttributeOption =  $AttributeOption;
    }

    public function topBrand($order)
    {
        $orderItems = $order->items;
        foreach ($orderItems as $key => $orderItem) {
                $products[]= $orderItem->product;
                $this->OrderBrandsRepository->create([
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
            $orderBrand = $this->OrderBrandsRepository->get()->toArray();
            // $orderBrand = $this->arrangeWithMaxBrandOrder($orderBrandData);

            foreach ($orderBrand as $product) {
                $product_id[] = $product['product_id'];

                $product_categories = $this->productRepository->with('categories')->findWhereIn('id', $product_id)->toArray();
            }

            foreach($product_categories as $totalData) {
                $brand = $this->AttributeOption->findOneWhere(['id' => $totalData['brand']]);

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
        } catch (Exception $e){
            throw $e;
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
                    ->select('rating', \DB::raw('count(*) as total'))
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
}

