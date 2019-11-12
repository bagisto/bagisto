<?php

namespace Webkul\Product\Repositories;

use Illuminate\Container\Container as App;
use Webkul\Core\Eloquent\Repository;

/**
 * ProductBundleOption Repository
 *
 * @author Jitendra Singh <jitendra@webkul.com>
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class ProductBundleOptionRepository extends Repository
{
    /**
     * ProductBundleOptionProductRepository object
     *
     * @var ProductBundleOptionProductRepository
     */
    protected $productBundleOptionProductRepository;

    /**
     * Create a new controller instance.
     *
     * @param  Webkul\Product\Repositories\ProductBundleOptionProductRepository $productBundleOptionProductRepository
     * @return void
     */
    public function __construct(
        ProductBundleOptionProductRepository $productBundleOptionProductRepository,
        App $app
    )
    {
        $this->productBundleOptionProductRepository = $productBundleOptionProductRepository;

        parent::__construct($app);
    }

    public function model()
    {
        return 'Webkul\Product\Contracts\ProductBundleOption';
    }

    /**
     * @param array   $data
     * @param Product $product
     * @return void
     */
    public function saveBundleOptons($data, $product)
    {
        $previousBundleOptionIds = $product->bundle_options()->pluck('id');

        if (isset($data['bundle_options'])) {
            foreach ($data['bundle_options'] as $bundleOptionId => $bundleOptionInputs) {
                if (str_contains($bundleOptionId, 'option_')) {
                    $productBundleOption = $this->create(array_merge([
                            'product_id' => $product->id,
                        ], $bundleOptionInputs));
                } else {
                    $productBundleOption = $this->find($bundleOptionId);

                    if (is_numeric($index = $previousBundleOptionIds->search($bundleOptionId)))
                        $previousBundleOptionIds->forget($index);

                    $this->update($bundleOptionInputs, $bundleOptionId);
                }

                $this->productBundleOptionProductRepository->saveBundleOptonProducts($bundleOptionInputs, $productBundleOption);
            }
        }

        foreach ($previousBundleOptionIds as $previousBundleOptionId) {
            $this->delete($previousBundleOptionId);
        }
    }
}