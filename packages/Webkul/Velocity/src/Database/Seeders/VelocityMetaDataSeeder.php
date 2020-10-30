<?php

namespace Webkul\Velocity\Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class VelocityMetaDataSeeder extends Seeder
{
    public function run()
    {
        $now = Carbon::now();

        DB::table('velocity_meta_data')->delete();

        DB::table('velocity_meta_data')->insert(
            [
                'id'                       => 1,
                'locale'                   => 'en',

                'header_content_count'     => "5",
                'channel'                  => "default",
                'home_page_content'        => "<p>@include('shop::home.advertisements.advertisement-four')@include('shop::home.featured-products') @include('shop::home.product-policy') @include('shop::home.advertisements.advertisement-three') @include('shop::home.new-products') @include('shop::home.advertisements.advertisement-two')</p>",
                'footer_left_content'      => __('velocity::app.admin.meta-data.footer-left-raw-content'),

                'footer_middle_content'    => '<div class="col-lg-6 col-md-12 col-sm-12 no-padding"><ul type="none"><li><a href="{!! url(\'page/about-us\') !!}">About Us</a></li><li><a href="{!! url(\'page/cutomer-service\') !!}">Customer Service</a></li><li><a href="{!! url(\'page/whats-new\') !!}">What&rsquo;s New</a></li><li><a href="{!! url(\'page/contact-us\') !!}">Contact Us </a></li></ul></div><div class="col-lg-6 col-md-12 col-sm-12 no-padding"><ul type="none"><li><a href="{!! url(\'page/return-policy\') !!}"> Order and Returns </a></li><li><a href="{!! url(\'page/payment-policy\') !!}"> Payment Policy </a></li><li><a href="{!! url(\'page/shipping-policy\') !!}"> Shipping Policy</a></li><li><a href="{!! url(\'page/privacy-policy\') !!}"> Privacy and Cookies Policy </a></li></ul></div>',
                'slider'                   => 1,

                'subscription_bar_content' => '<div class="social-icons col-lg-6"><a href="https://webkul.com" target="_blank" class="unset" rel="noopener noreferrer"><i class="fs24 within-circle rango-facebook" title="facebook"></i> </a> <a href="https://webkul.com" target="_blank" class="unset" rel="noopener noreferrer"><i class="fs24 within-circle rango-twitter" title="twitter"></i> </a> <a href="https://webkul.com" target="_blank" class="unset" rel="noopener noreferrer"><i class="fs24 within-circle rango-linked-in" title="linkedin"></i> </a> <a href="https://webkul.com" target="_blank" class="unset" rel="noopener noreferrer"><i class="fs24 within-circle rango-pintrest" title="Pinterest"></i> </a> <a href="https://webkul.com" target="_blank" class="unset" rel="noopener noreferrer"><i class="fs24 within-circle rango-youtube" title="Youtube"></i> </a> <a href="https://webkul.com" target="_blank" class="unset" rel="noopener noreferrer"><i class="fs24 within-circle rango-instagram" title="instagram"></i></a></div>',

                'product_policy'           => '<div class="row col-12 remove-padding-margin"><div class="col-lg-4 col-sm-12 product-policy-wrapper"><div class="card"><div class="policy"><div class="left"><i class="rango-van-ship fs40"></i></div> <div class="right"><span class="font-setting fs20">Free Shipping on Order $20 or More</span></div></div></div></div> <div class="col-lg-4 col-sm-12 product-policy-wrapper"><div class="card"><div class="policy"><div class="left"><i class="rango-exchnage fs40"></i></div> <div class="right"><span class="font-setting fs20">Product Replace &amp; Return Available </span></div></div></div></div> <div class="col-lg-4 col-sm-12 product-policy-wrapper"><div class="card"><div class="policy"><div class="left"><i class="rango-exchnage fs40"></i></div> <div class="right"><span class="font-setting fs20">Product Exchange and EMI Available </span></div></div></div></div></div>',
            ]
        );


        DB::table('core_config')->insert([
            [
                'code'         => 'general.content.shop.compare_option',
                'value'        => '1',
                'channel_code' => 'default',
                'locale_code'  => 'en',
                'created_at'   => $now,
                'updated_at'   => $now,
            ],
            [
                'code'         => 'general.content.shop.compare_option',
                'value'        => '1',
                'channel_code' => 'default',
                'locale_code'  => 'fr',
                'created_at'   => $now,
                'updated_at'   => $now,
            ],
            [
                'code'         => 'general.content.shop.compare_option',
                'value'        => '1',
                'channel_code' => 'default',
                'locale_code'  => 'ar',
                'created_at'   => $now,
                'updated_at'   => $now,
            ],
            [
                'code'         => 'general.content.shop.compare_option',
                'value'        => '1',
                'channel_code' => 'default',
                'locale_code'  => 'de',
                'created_at'   => $now,
                'updated_at'   => $now,
            ],
            [
                'code'         => 'general.content.shop.compare_option',
                'value'        => '1',
                'channel_code' => 'default',
                'locale_code'  => 'es',
                'created_at'   => $now,
                'updated_at'   => $now,
            ],
            [
                'code'         => 'general.content.shop.compare_option',
                'value'        => '1',
                'channel_code' => 'default',
                'locale_code'  => 'fa',
                'created_at'   => $now,
                'updated_at'   => $now,
            ],
            [
                'code'         => 'general.content.shop.compare_option',
                'value'        => '1',
                'channel_code' => 'default',
                'locale_code'  => 'it',
                'created_at'   => $now,
                'updated_at'   => $now,
            ],
            [
                'code'         => 'general.content.shop.compare_option',
                'value'        => '1',
                'channel_code' => 'default',
                'locale_code'  => 'ja',
                'created_at'   => $now,
                'updated_at'   => $now,
            ],
            [
                'code'         => 'general.content.shop.compare_option',
                'value'        => '1',
                'channel_code' => 'default',
                'locale_code'  => 'nl',
                'created_at'   => $now,
                'updated_at'   => $now,
            ],
            [
                'code'         => 'general.content.shop.compare_option',
                'value'        => '1',
                'channel_code' => 'default',
                'locale_code'  => 'pl',
                'created_at'   => $now,
                'updated_at'   => $now,
            ],
            [
                'code'         => 'general.content.shop.compare_option',
                'value'        => '1',
                'channel_code' => 'default',
                'locale_code'  => 'pt_BR',
                'created_at'   => $now,
                'updated_at'   => $now,
            ],
            [
                'code'         => 'general.content.shop.compare_option',
                'value'        => '1',
                'channel_code' => 'default',
                'locale_code'  => 'tr',
                'created_at'   => $now,
                'updated_at'   => $now,
            ],

            //Wishlist show config data
            [
                'code'         => 'general.content.shop.wishlist_option',
                'value'        => '1',
                'channel_code' => 'default',
                'locale_code'  => 'en',
                'created_at'   => $now,
                'updated_at'   => $now,
            ],
            [
                'code'         => 'general.content.shop.wishlist_option',
                'value'        => '1',
                'channel_code' => 'default',
                'locale_code'  => 'fr',
                'created_at'   => $now,
                'updated_at'   => $now,
            ],
            [
                'code'         => 'general.content.shop.wishlist_option',
                'value'        => '1',
                'channel_code' => 'default',
                'locale_code'  => 'ar',
                'created_at'   => $now,
                'updated_at'   => $now,
            ],
            [
                'code'         => 'general.content.shop.wishlist_option',
                'value'        => '1',
                'channel_code' => 'default',
                'locale_code'  => 'de',
                'created_at'   => $now,
                'updated_at'   => $now,
            ],
            [
                'code'         => 'general.content.shop.wishlist_option',
                'value'        => '1',
                'channel_code' => 'default',
                'locale_code'  => 'es',
                'created_at'   => $now,
                'updated_at'   => $now,
            ],
            [
                'code'         => 'general.content.shop.wishlist_option',
                'value'        => '1',
                'channel_code' => 'default',
                'locale_code'  => 'fa',
                'created_at'   => $now,
                'updated_at'   => $now,
            ],
            [
                'code'         => 'general.content.shop.wishlist_option',
                'value'        => '1',
                'channel_code' => 'default',
                'locale_code'  => 'it',
                'created_at'   => $now,
                'updated_at'   => $now,
            ],
            [
                'code'         => 'general.content.shop.wishlist_option',
                'value'        => '1',
                'channel_code' => 'default',
                'locale_code'  => 'ja',
                'created_at'   => $now,
                'updated_at'   => $now,
            ],
            [
                'code'         => 'general.content.shop.wishlist_option',
                'value'        => '1',
                'channel_code' => 'default',
                'locale_code'  => 'nl',
                'created_at'   => $now,
                'updated_at'   => $now,
            ],
            [
                'code'         => 'general.content.shop.wishlist_option',
                'value'        => '1',
                'channel_code' => 'default',
                'locale_code'  => 'pl',
                'created_at'   => $now,
                'updated_at'   => $now,
            ],
            [
                'code'         => 'general.content.shop.wishlist_option',
                'value'        => '1',
                'channel_code' => 'default',
                'locale_code'  => 'pt_BR',
                'created_at'   => $now,
                'updated_at'   => $now,
            ],
            [
                'code'         => 'general.content.shop.wishlist_option',
                'value'        => '1',
                'channel_code' => 'default',
                'locale_code'  => 'tr',
                'created_at'   => $now,
                'updated_at'   => $now,
            ],

            /* Image search core config data starts here */
            [
                'code'         => 'general.content.shop.image_search',
                'value'        => '1',
                'channel_code' => 'default',
                'locale_code'  => 'en',
                'created_at'   => $now,
                'updated_at'   => $now,
            ],
            [
                'code'         => 'general.content.shop.image_search',
                'value'        => '1',
                'channel_code' => 'default',
                'locale_code'  => 'fr',
                'created_at'   => $now,
                'updated_at'   => $now,
            ],
            [
                'code'         => 'general.content.shop.image_search',
                'value'        => '1',
                'channel_code' => 'default',
                'locale_code'  => 'ar',
                'created_at'   => $now,
                'updated_at'   => $now,
            ],
            [
                'code'         => 'general.content.shop.image_search',
                'value'        => '1',
                'channel_code' => 'default',
                'locale_code'  => 'de',
                'created_at'   => $now,
                'updated_at'   => $now,
            ],
            [
                'code'         => 'general.content.shop.image_search',
                'value'        => '1',
                'channel_code' => 'default',
                'locale_code'  => 'es',
                'created_at'   => $now,
                'updated_at'   => $now,
            ],
            [
                'code'         => 'general.content.shop.image_search',
                'value'        => '1',
                'channel_code' => 'default',
                'locale_code'  => 'fa',
                'created_at'   => $now,
                'updated_at'   => $now,
            ],
            [
                'code'         => 'general.content.shop.image_search',
                'value'        => '1',
                'channel_code' => 'default',
                'locale_code'  => 'it',
                'created_at'   => $now,
                'updated_at'   => $now,
            ],
            [
                'code'         => 'general.content.shop.image_search',
                'value'        => '1',
                'channel_code' => 'default',
                'locale_code'  => 'ja',
                'created_at'   => $now,
                'updated_at'   => $now,
            ],
            [
                'code'         => 'general.content.shop.image_search',
                'value'        => '1',
                'channel_code' => 'default',
                'locale_code'  => 'nl',
                'created_at'   => $now,
                'updated_at'   => $now,
            ],
            [
                'code'         => 'general.content.shop.image_search',
                'value'        => '1',
                'channel_code' => 'default',
                'locale_code'  => 'pl',
                'created_at'   => $now,
                'updated_at'   => $now,
            ],
            [
                'code'         => 'general.content.shop.image_search',
                'value'        => '1',
                'channel_code' => 'default',
                'locale_code'  => 'pt_BR',
                'created_at'   => $now,
                'updated_at'   => $now,
            ],
            [
                'code'         => 'general.content.shop.image_search',
                'value'        => '1',
                'channel_code' => 'default',
                'locale_code'  => 'tr',
                'created_at'   => $now,
                'updated_at'   => $now,
            ],
            /* Image search core config data ends here */
        ]);
    }
}