<?php

namespace Webkul\Velocity\Http\Controllers\Shop;

class ComparisonController extends Controller
{
    /**
     * Method for customers to get products in comparison.
     *
     * @return \Illuminate\Http\Response|\Illuminate\View\View
     */
    public function getComparisonList()
    {
        if (! core()->getConfigData('general.content.shop.compare_option')) {
            abort(404);
        } else {
            if (request()->get('data')) {
                $productCollection = [];

                if (auth()->guard('customer')->user()) {
                    $productCollection = $this->compareProductsRepository
                        ->leftJoin(
                            'product_flat',
                            'velocity_customer_compare_products.product_flat_id',
                            'product_flat.id'
                        )
                        ->where('customer_id', auth()->guard('customer')->user()->id)
                        ->get();

                    $items = $productCollection->map(function ($product) {
                        return $product->id;
                    })->join('&');

                    $productCollection = ! empty($items)
                        ? $this->velocityHelper->fetchProductCollection($items)
                        : [];
                } else {
                    /* for product details */
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

            $productFlatRepository = app('\Webkul\Product\Models\ProductFlat');

            $productFlat = $productFlatRepository
                ->where('id', $productId)
                ->orWhere('parent_id', $productId)
                ->orWhere('id', $productId)
                ->get()
                ->first();
                            
            if ($productFlat == null) {
                return response()->json([
                    'status'  => 'warning',
                    'message' => trans('customer::app.product-removed'),
                    'label'   => trans('velocity::app.shop.general.alert.warning'),
                ]);
            }

            if ($productFlat) {
                $productId = $productFlat->id;

                $this->compareProductsRepository->create([
                    'customer_id'     => $customerId,
                    'product_flat_id' => $productId,
                ]);
            }

            return response()->json([
                'status'  => 'success',
                'message' => trans('velocity::app.customer.compare.added'),
                'label'   => trans('velocity::app.shop.general.alert.success'),
            ]);
        } else {
            return response()->json([
                'status'  => 'success',
                'label'   => trans('velocity::app.shop.general.alert.success'),
                'message' => trans('velocity::app.customer.compare.already_added'),
            ]);
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

            $message = trans('velocity::app.customer.compare.removed-all');
        } else {
            // delete individual
            $this->compareProductsRepository->deleteWhere([
                'product_flat_id' => request()->get('productId'),
                'customer_id'     => auth()->guard('customer')->user()->id,
            ]);
            
            $message = trans('velocity::app.customer.compare.removed');
        }

        return [
            'status'  => 'success',
            'message' => $message,
            'label'   => trans('velocity::app.shop.general.alert.success'),
        ];
    }
}