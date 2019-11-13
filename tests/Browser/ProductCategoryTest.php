<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ProductCategoryTest extends DuskTestCase
{
    /**
    * A basic browser test example.
    *
    * @return void
    */
    public function testGuestAddToCart()
    {
        $categories = app('Webkul\Category\Repositories\CategoryRepository')->all();
        $products = app('Webkul\Product\Repositories\ProductRepository')->all();

        $slugs = array();

        foreach ($categories as $category) {
            if ($category->slug != 'root') {
                array_push($slugs, $category->slug);
            }
        }

        $slugIndex = array_rand($slugs);
        $testSlug = $slugs[$slugIndex];
        $testProduct = array();

        foreach ($products as $product) {
            $categories = $product->categories;

            if ($categories->last()->slug == $testSlug) {
                array_push($testProduct, ['name' => $product->name, 'url_key' => $product->url_key]);

                break;
            }
        }

        $this->browse(function (Browser $browser) use($testSlug, $testProduct) {
            $browser->visit(route('shop.categories.index', $testSlug));
            $browser->assertSeeLink($testProduct[0]['name']);
            $browser->pause(5000);
        });
    }
}