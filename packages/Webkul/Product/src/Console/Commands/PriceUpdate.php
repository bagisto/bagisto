<?php

namespace Webkul\Product\Console\Commands;

use Illuminate\Console\Command;
use Webkul\Product\Repositories\ProductFlatRepository;

class PriceUpdate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'product:price:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Automatically updates product information (eg. min_price and max_price)';

    /**
     * Create a new command instance.
     *
     * @param  \Webkul\Product\Repositories\ProductFlatRepository  $productFlatRepository
     * @return void
     */
    public function __construct(protected ProductFlatRepository $productFlatRepository)
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $products = $this->productFlatRepository->findWhere([['special_price', '>', 0]]);

        foreach ($products as $product) {
            request()->request->set('channel', $product->channel);

            $product->min_price = $product->getTypeInstance()->getMinimalPrice();

            $product->max_price = $product->getTypeInstance()->getMaximumPrice();

            $product->save();

            if ($product->parent) {
                $product->parent->min_price = $product->parent->getTypeInstance()->getMinimalPrice();

                $product->parent->max_price = $product->parent->getTypeInstance()->getMaximumPrice();
    
                $product->parent->save();
            } else {
                $bundleProducts = $this->productFlatRepository->getModel()
                    ->addSelect('product_flat.*')
                    ->distinct()
                    ->leftJoin('products', 'product_flat.product_id', 'products.id')
                    ->leftJoin('product_bundle_options', 'products.id', 'product_bundle_options.product_id')
                    ->leftJoin('product_bundle_option_products', 'product_bundle_options.id', 'product_bundle_option_productsproduct_bundle_option_id')
                    ->where('product_bundle_option_products.product_id', $product->product_id)
                    ->get();

                foreach ($bundleProducts as $bundleProduct) {
                    $bundleProduct->min_price = $bundleProduct->getTypeInstance()->getMinimalPrice();

                    $bundleProduct->max_price = $bundleProduct->getTypeInstance()->getMaximumPrice();
        
                    $bundleProduct->save();
                }
            }
        }
    }
}