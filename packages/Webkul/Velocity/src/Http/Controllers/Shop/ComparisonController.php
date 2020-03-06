<?php

namespace Webkul\Velocity\Http\Controllers\Shop;

use Webkul\Velocity\Helpers\Helper;
use Webkul\Product\Repositories\ProductRepository;
use Webkul\Velocity\Repositories\VelocityCustomerCompareProductRepository as CustomerCompareProductRepository;

class ComparisonController extends Controller
{
    /**
     * function for customers to get products in comparison.
     *
     * @return \Illuminate\Http\Response|\Illuminate\View\View
     */
    public function getComparisonList()
    {
        if (request()->get('data')) {
            $productSlugs = null;
            $productCollection = [];

            if (auth()->guard('customer')->user()) {
                $productCollection = $this->compareProductsRepository
                    ->leftJoin(
                        'product_flat',
                        'velocity_customer_compare_products.product_flat_id',
                        'product_flat.id'
                    )
                    ->where('customer_id', auth()->guard('customer')->user()->id)
                    ->get()
                    ->toArray();

                foreach ($productCollection as $index => $customerCompare) {
                    $product = $this->productRepository->find($customerCompare['product_id']);
                    $formattedProduct = $this->velocityHelper->formatProduct($product);

                    $productCollection[$index]['image'] = $formattedProduct['image'];
                    $productCollection[$index]['priceHTML'] = $formattedProduct['priceHTML'];
                    $productCollection[$index]['addToCartHtml'] = $formattedProduct['addToCartHtml'];
                }
            } else {
                // for product details
                if ($items = request()->get('items')) {
                    $productCollection = $this->velocityHelper->fetchProductCollection($items);
                }
            }

            $response = [
                'status'   => 'success',
                'products' => $productCollection,
            ];
        } else {
            $response = view($this->_config['view']);
        }

        return $response;
    }

    /**
     * function for customers to add product in comparison.
     *
     * @return \Illuminate\Http\Response
     */
    public function addCompareProduct()
    {
        $productId = request()->get('productId');

        $customerId = auth()->guard('customer')->user()->id;

        $compareProduct = $this->compareProductsRepository->findOneByField([
            'customer_id'     => $customerId,
            'product_flat_id' => $productId,
        ]);

        if (! $compareProduct) {
            // insert new row
            $this->compareProductsRepository->create([
                'customer_id'     => $customerId,
                'product_flat_id' => $productId,
            ]);

            return response()->json([
                'status'  => 'success',
                'message' => trans('velocity::app.customer.compare.added'),
                'label'   => trans('velocity::app.shop.general.alert.success'),
            ], 201);
        } else {
            return response()->json([
                'status'  => 'success',
                'label'   => trans('velocity::app.shop.general.alert.success'),
                'message' => trans('velocity::app.customer.compare.already_added'),
            ], 200);
        }
    }

    /**
     * function for customers to delete product in comparison.
     *
     * @return \Illuminate\Http\Response
     */
    public function deleteComparisonProduct()
    {
        // either delete all or individual
        if (request()->get('productId') == 'all') {
            // delete all
            $customerId = auth()->guard('customer')->user()->id;
            $this->compareProductsRepository->deleteWhere([
                'customer_id' => auth()->guard('customer')->user()->id,
            ]);
        } else {
            // delete individual
            $this->compareProductsRepository->deleteWhere([
                'product_flat_id' => request()->get('productId'),
                'customer_id'     => auth()->guard('customer')->user()->id,
            ]);
        }

        return [
            'status'  => 'success',
            'message' => trans('velocity::app.customer.compare.removed'),
            'label'   => trans('velocity::app.shop.general.alert.success'),
        ];
    }
}