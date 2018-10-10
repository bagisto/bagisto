<?php

namespace Webkul\Shop\Http\ViewComposers;

use Illuminate\View\View;
use Illuminate\Support\Collection;
use Webkul\Product\Repositories\ProductRepository as Product;

/**
 * New Products page
 *
 * @author    Jitendra Singh <jitendra@webkul.com>
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class NewProductListComposer
{
    /**
     * ProductRepository object
     *
     * @var array
     */
    protected $product;

    /**
     * Bind data to the view.
     *
     * @param  View  $view
     * @return void
     */
    public function __construct(Product $product)
    {
        $this->product = $product;
    }

    /**
     * Bind data to the view.
     *
     * @param  View  $view
     * @return void
     */
    public function compose(View $view)
    {
        $products = $this->product->getNewProducts();

        $view->with('products', $products);
    }
}
