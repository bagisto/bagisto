<?php

namespace Webkul\RMA\Listeners;

use Illuminate\Foundation\Validation\ValidatesRequests;
use Webkul\Product\Repositories\ProductRepository;
use Webkul\Product\Contracts\Product as ProductContract;

class Product
{
    use ValidatesRequests;

    /**
     * Create a new listener instance.
     *
     * @return void
     */
    public function __construct(
        protected ProductRepository $productRepository,
    ) {
    }

    /**
     * Set rma rules into particular product .
     */
    public function afterUpdate($product): void
    {
        if (! isset(request()->allow_rma)) {
            return;
        }

        $allowRma = request()->boolean('allow_rma', false);

        $data = [
            'allow_rma' => $allowRma,
        ];

        if ($allowRma) {
            $data['rma_rules'] = request()->input('rma_rules');
        }

        $this->productRepository->where('id', $product->id)->update($data);
    }
}
