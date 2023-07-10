<?php

namespace Webkul\Velocity\Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class VelocityMetaDataSeeder extends Seeder
{
    /**
     * Run seeder.
     *
     * @return void
     */
    public function run()
    {
        $now = Carbon::now();

        DB::table('velocity_meta_data')->delete();

        DB::table('velocity_meta_data')->insert(
            [
                'id'                       => 1,
                'locale'                   => 'en',
                'header_content_count'     => '5',
                'channel'                  => 'default',
                'home_page_content'        => '',
                'footer_left_content'      => '',
                'footer_middle_content'    => '',
                'slider'                   => 1,
                'subscription_bar_content' => '<div class="social-icons col-lg-6"><a href="https://facebook.com" target="_blank" class="unset" rel="noopener noreferrer"><i class="fs24 within-circle rango-facebook" title="facebook"></i> </a> <a href="https://twitter.com" target="_blank" class="unset" rel="noopener noreferrer"><i class="fs24 within-circle rango-twitter" title="twitter"></i> </a> <a href="https://linkedin.com" target="_blank" class="unset" rel="noopener noreferrer"><i class="fs24 within-circle rango-linked-in" title="linkedin"></i> </a> <a href="https://pintrest.com" target="_blank" class="unset" rel="noopener noreferrer"><i class="fs24 within-circle rango-pintrest" title="Pinterest"></i> </a> <a href="https://youtube.com" target="_blank" class="unset" rel="noopener noreferrer"><i class="fs24 within-circle rango-youtube" title="Youtube"></i> </a> <a href="https://instagram.com" target="_blank" class="unset" rel="noopener noreferrer"><i class="fs24 within-circle rango-instagram" title="instagram"></i></a></div>',
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
            ], [
                'code'         => 'general.content.shop.compare_option',
                'value'        => '1',
                'channel_code' => 'default',
                'locale_code'  => 'fr',
                'created_at'   => $now,
                'updated_at'   => $now,
            ], [
                'code'         => 'general.content.shop.compare_option',
                'value'        => '1',
                'channel_code' => 'default',
                'locale_code'  => 'ar',
                'created_at'   => $now,
                'updated_at'   => $now,
            ], [
                'code'         => 'general.content.shop.compare_option',
                'value'        => '1',
                'channel_code' => 'default',
                'locale_code'  => 'de',
                'created_at'   => $now,
                'updated_at'   => $now,
            ], [
                'code'         => 'general.content.shop.compare_option',
                'value'        => '1',
                'channel_code' => 'default',
                'locale_code'  => 'es',
                'created_at'   => $now,
                'updated_at'   => $now,
            ], [
                'code'         => 'general.content.shop.compare_option',
                'value'        => '1',
                'channel_code' => 'default',
                'locale_code'  => 'fa',
                'created_at'   => $now,
                'updated_at'   => $now,
            ], [
                'code'         => 'general.content.shop.compare_option',
                'value'        => '1',
                'channel_code' => 'default',
                'locale_code'  => 'it',
                'created_at'   => $now,
                'updated_at'   => $now,
            ], [
                'code'         => 'general.content.shop.compare_option',
                'value'        => '1',
                'channel_code' => 'default',
                'locale_code'  => 'ja',
                'created_at'   => $now,
                'updated_at'   => $now,
            ], [
                'code'         => 'general.content.shop.compare_option',
                'value'        => '1',
                'channel_code' => 'default',
                'locale_code'  => 'nl',
                'created_at'   => $now,
                'updated_at'   => $now,
            ], [
                'code'         => 'general.content.shop.compare_option',
                'value'        => '1',
                'channel_code' => 'default',
                'locale_code'  => 'pl',
                'created_at'   => $now,
                'updated_at'   => $now,
            ], [
                'code'         => 'general.content.shop.compare_option',
                'value'        => '1',
                'channel_code' => 'default',
                'locale_code'  => 'pt_BR',
                'created_at'   => $now,
                'updated_at'   => $now,
            ], [
                'code'         => 'general.content.shop.compare_option',
                'value'        => '1',
                'channel_code' => 'default',
                'locale_code'  => 'tr',
                'created_at'   => $now,
                'updated_at'   => $now,
            ], [
                'code'         => 'general.content.shop.wishlist_option',
                'value'        => '1',
                'channel_code' => 'default',
                'locale_code'  => 'en',
                'created_at'   => $now,
                'updated_at'   => $now,
            ], [
                'code'         => 'general.content.shop.wishlist_option',
                'value'        => '1',
                'channel_code' => 'default',
                'locale_code'  => 'fr',
                'created_at'   => $now,
                'updated_at'   => $now,
            ], [
                'code'         => 'general.content.shop.wishlist_option',
                'value'        => '1',
                'channel_code' => 'default',
                'locale_code'  => 'ar',
                'created_at'   => $now,
                'updated_at'   => $now,
            ], [
                'code'         => 'general.content.shop.wishlist_option',
                'value'        => '1',
                'channel_code' => 'default',
                'locale_code'  => 'de',
                'created_at'   => $now,
                'updated_at'   => $now,
            ], [
                'code'         => 'general.content.shop.wishlist_option',
                'value'        => '1',
                'channel_code' => 'default',
                'locale_code'  => 'es',
                'created_at'   => $now,
                'updated_at'   => $now,
            ], [
                'code'         => 'general.content.shop.wishlist_option',
                'value'        => '1',
                'channel_code' => 'default',
                'locale_code'  => 'fa',
                'created_at'   => $now,
                'updated_at'   => $now,
            ], [
                'code'         => 'general.content.shop.wishlist_option',
                'value'        => '1',
                'channel_code' => 'default',
                'locale_code'  => 'it',
                'created_at'   => $now,
                'updated_at'   => $now,
            ], [
                'code'         => 'general.content.shop.wishlist_option',
                'value'        => '1',
                'channel_code' => 'default',
                'locale_code'  => 'ja',
                'created_at'   => $now,
                'updated_at'   => $now,
            ], [
                'code'         => 'general.content.shop.wishlist_option',
                'value'        => '1',
                'channel_code' => 'default',
                'locale_code'  => 'nl',
                'created_at'   => $now,
                'updated_at'   => $now,
            ], [
                'code'         => 'general.content.shop.wishlist_option',
                'value'        => '1',
                'channel_code' => 'default',
                'locale_code'  => 'pl',
                'created_at'   => $now,
                'updated_at'   => $now,
            ], [
                'code'         => 'general.content.shop.wishlist_option',
                'value'        => '1',
                'channel_code' => 'default',
                'locale_code'  => 'pt_BR',
                'created_at'   => $now,
                'updated_at'   => $now,
            ], [
                'code'         => 'general.content.shop.wishlist_option',
                'value'        => '1',
                'channel_code' => 'default',
                'locale_code'  => 'tr',
                'created_at'   => $now,
                'updated_at'   => $now,
            ], [
                'code'         => 'general.content.shop.image_search',
                'value'        => '1',
                'channel_code' => 'default',
                'locale_code'  => 'en',
                'created_at'   => $now,
                'updated_at'   => $now,
            ], [
                'code'         => 'general.content.shop.image_search',
                'value'        => '1',
                'channel_code' => 'default',
                'locale_code'  => 'fr',
                'created_at'   => $now,
                'updated_at'   => $now,
            ], [
                'code'         => 'general.content.shop.image_search',
                'value'        => '1',
                'channel_code' => 'default',
                'locale_code'  => 'ar',
                'created_at'   => $now,
                'updated_at'   => $now,
            ], [
                'code'         => 'general.content.shop.image_search',
                'value'        => '1',
                'channel_code' => 'default',
                'locale_code'  => 'de',
                'created_at'   => $now,
                'updated_at'   => $now,
            ], [
                'code'         => 'general.content.shop.image_search',
                'value'        => '1',
                'channel_code' => 'default',
                'locale_code'  => 'es',
                'created_at'   => $now,
                'updated_at'   => $now,
            ], [
                'code'         => 'general.content.shop.image_search',
                'value'        => '1',
                'channel_code' => 'default',
                'locale_code'  => 'fa',
                'created_at'   => $now,
                'updated_at'   => $now,
            ], [
                'code'         => 'general.content.shop.image_search',
                'value'        => '1',
                'channel_code' => 'default',
                'locale_code'  => 'it',
                'created_at'   => $now,
                'updated_at'   => $now,
            ], [
                'code'         => 'general.content.shop.image_search',
                'value'        => '1',
                'channel_code' => 'default',
                'locale_code'  => 'ja',
                'created_at'   => $now,
                'updated_at'   => $now,
            ], [
                'code'         => 'general.content.shop.image_search',
                'value'        => '1',
                'channel_code' => 'default',
                'locale_code'  => 'nl',
                'created_at'   => $now,
                'updated_at'   => $now,
            ], [
                'code'         => 'general.content.shop.image_search',
                'value'        => '1',
                'channel_code' => 'default',
                'locale_code'  => 'pl',
                'created_at'   => $now,
                'updated_at'   => $now,
            ], [
                'code'         => 'general.content.shop.image_search',
                'value'        => '1',
                'channel_code' => 'default',
                'locale_code'  => 'pt_BR',
                'created_at'   => $now,
                'updated_at'   => $now,
            ], [
                'code'         => 'general.content.shop.image_search',
                'value'        => '1',
                'channel_code' => 'default',
                'locale_code'  => 'tr',
                'created_at'   => $now,
                'updated_at'   => $now,
            ],
        ]);
    }
}
