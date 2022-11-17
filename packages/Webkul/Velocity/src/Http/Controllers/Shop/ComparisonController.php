<?php

namespace Webkul\Velocity\Http\Controllers\Shop;

use Webkul\Product\Repositories\ProductRepository;
use Webkul\Velocity\Repositories\VelocityCustomerCompareProductRepository as CustomerCompareProductRepository;
use Webkul\Velocity\Helpers\Helper;

class ComparisonController extends Controller
{
    /**
     * Contains route related configuration
     *
     * @var array
     */
    protected $_config;
    
    /**
     * Create a new controller instance.
     *
     * @param  \Webkul\Product\Repositories\ProductRepository  $productRepository
     * @param  \Webkul\Velocity\Repositories\VelocityCustomerCompareProductRepository  $compareProductsRepository
     * @param  \Webkul\Velocity\Helpers\Helper  $velocityHelper
     *
     * @return void
     */
    public function __construct(
        protected ProductRepository $productRepository,
        protected CustomerCompareProductRepository $compareProductsRepository,
        protected Helper $velocityHelper
    )
    {
        $this->_config = request('_config');
    }

    /**
     * Method for customers to get products in comparison.
     *
     * @return \Illuminate\Http\Response|\Illuminate\View\View
     */
    public function getComparisonList()
    {
        if (! core()->getConfigData('general.content.shop.compare_option')) {
            abort(404);
        }

        if (! request()->get('data')) {
            return view($this->_config['view']);;
        }

        $productCollection = [];

        if (auth()->guard('customer')->user()) {
            $productCollection = $this->compareProductsRepository
                ->leftJoin(
                    'products',
                    'velocity_customer_compare_products.product_id',
                    'products.id'
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

        return [
            'status'   => 'success',
            'products' => $productCollection,
        ];
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
            'customer_id' => $customerId,
            'product_id'  => $productId,
        ]);

        if ($compareProduct) {
            return response()->json([
                'status'  => 'warning',
                'label'   => trans('velocity::app.shop.general.alert.warning'),
                'message' => trans('velocity::app.customer.compare.already_added'),
            ]);
        }

        $product = $this->productRepository->find($productId);
                        
        if (! $product) {
            return response()->json([
                'status'  => 'warning',
                'message' => trans('customer::app.product-removed'),
                'label'   => trans('velocity::app.shop.general.alert.warning'),
            ]);
        }

        $this->compareProductsRepository->create([
            'customer_id' => $customerId,
            'product_id'  => $product->id,
        ]);

        return response()->json([
            'status'  => 'success',
            'message' => trans('velocity::app.customer.compare.added'),
            'label'   => trans('velocity::app.shop.general.alert.success'),
        ]);
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
                'product_id'  => request()->get('productId'),
                'customer_id' => auth()->guard('customer')->user()->id,
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