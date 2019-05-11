<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class GuestShopTest extends DuskTestCase
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
        dd($testSlug);
        foreach ($products as $product) {
            $categories = $product->categories;

            if ($categories->last()->slug == $testSlug) {
                array_push($testProduct, $product);
            }
        }

        $this->browse(function (Browser $browser) use($testSlug,$product) {
            $browser->visit(route('shop.categories.index', $testSlug));
            $browser->assertSeeLink($linkText);
            $browser->pause(4000);
        });
    }
}