<?php

namespace Webkul\Core\Database\Seeders;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;

class ChannelTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('channels')->delete();

        DB::table('channels')->insert([
            'id'                => 1,
            'code'              => 'default',
            'name'              => 'Default',
            'theme'             => 'velocity',
            'hostname'          => config('app.url'),
            'root_category_id'  => 1,
            'home_page_content' => '<p>@include("shop::home.slider") @include("shop::home.featured-products") @include("shop::home.new-products")</p>
            <div class="banner-container">
            <div class="left-banner"><img data-src="themes/default/assets/images/1.webp" class="lazyload" alt="test" width="720" height="720" /></div>
            <div class="right-banner"><img data-src="themes/default/assets/images/2.webp" class="lazyload" alt="test" width="460" height="330" /> <img data-src="themes/default/assets/images/3.webp" class="lazyload" alt="test" width="460" height="330" /></div>
            </div>',
            'footer_content'    => '<div class="list-container"><span class="list-heading">Quick Links</span><ul class="list-group"><li><a href="@php echo route(\'shop.cms.page\', \'about-us\') @endphp">About Us</a></li><li><a href="@php echo route(\'shop.cms.page\', \'return-policy\') @endphp">Return Policy</a></li><li><a href="@php echo route(\'shop.cms.page\', \'refund-policy\') @endphp">Refund Policy</a></li><li><a href="@php echo route(\'shop.cms.page\', \'terms-conditions\') @endphp">Terms and conditions</a></li><li><a href="@php echo route(\'shop.cms.page\', \'terms-of-use\') @endphp">Terms of Use</a></li><li><a href="@php echo route(\'shop.cms.page\', \'contact-us\') @endphp">Contact Us</a></li></ul></div><div class="list-container"><span class="list-heading">Connect With Us</span><ul class="list-group"><li><a href="#"><span class="icon icon-facebook"></span>Facebook </a></li><li><a href="#"><span class="icon icon-twitter"></span> Twitter </a></li><li><a href="#"><span class="icon icon-instagram"></span> Instagram </a></li><li><a href="#"> <span class="icon icon-google-plus"></span>Google+ </a></li><li><a href="#"> <span class="icon icon-linkedin"></span>LinkedIn </a></li></ul></div>',
            'name'              => 'Default',
            'default_locale_id' => 1,
            'base_currency_id'  => 1,
            'home_seo'          => '{"meta_title": "Demo store", "meta_keywords": "Demo store meta keyword", "meta_description": "Demo store meta description"}',
        ]);

        DB::table('channel_currencies')->insert([
            'channel_id'  => 1,
            'currency_id' => 1,
        ]);

        DB::table('channel_locales')->insert([
            'channel_id' => 1,
            'locale_id'  => 1,
        ]);

        DB::table('channel_inventory_sources')->insert([
            'channel_id'          => 1,
            'inventory_source_id' => 1,
        ]);
    }
}
