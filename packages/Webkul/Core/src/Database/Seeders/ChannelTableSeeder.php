<?php

namespace Webkul\Core\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ChannelTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('channels')->delete();

        DB::table('channels')->insert([
            'id'                => 1,
            'code'              => 'default',
            'theme'             => 'velocity',
            'hostname'          => config('app.url'),
            'root_category_id'  => 1,
            'default_locale_id' => 1,
            'base_currency_id'  => 1,
        ]);

        DB::table('channel_translations')->insert([
            [
                'id'                => 1,
                'channel_id'        => 1,
                'locale'            => 'en',
                'name'              => 'Default',
                'home_page_content' => '',
                'footer_content'    => '
                    <div class="list-container">
                        <span class="list-heading">Quick Links</span>
                        <ul class="list-group">
                            <li><a href="'.route('shop.cms.page', 'about-us').'">About Us</a></li>
                            <li><a href="'.route('shop.cms.page', 'return-policy').'">Return Policy</a></li>
                            <li><a href="'.route('shop.cms.page', 'refund-policy') .'">Refund Policy</a></li>
                            <li><a href="'.route('shop.cms.page', 'terms-conditions').'">Terms and conditions</a></li>
                            <li><a href="'.route('shop.cms.page', 'terms-of-use').'">Terms of Use</a></li>
                            <li><a href="'.route('shop.cms.page', 'contact-us') .'">Contact Us</a></li>
                        </ul>
                    </div>
                    <div class="list-container">
                        <span class="list-heading">Connect With Us</span>
                            <ul class="list-group">
                                <li><a href="#"><span class="icon icon-facebook"></span>Facebook </a></li>
                                <li><a href="#"><span class="icon icon-twitter"></span> Twitter </a></li>
                                <li><a href="#"><span class="icon icon-instagram"></span> Instagram </a></li>
                                <li><a href="#"> <span class="icon icon-google-plus"></span>Google+ </a></li>
                                <li><a href="#"> <span class="icon icon-linkedin"></span>LinkedIn </a></li>
                            </ul>
                        </div>
                ',
                'home_seo'          => '{"meta_title": "Demo store", "meta_keywords": "Demo store meta keyword", "meta_description": "Demo store meta description"}',
            ], [
                'id'                => 2,
                'channel_id'        => 1,
                'locale'            => 'fr',
                'name'              => 'Default',
                'home_page_content' => '
                    <p>@include("shop::home.slider") @include("shop::home.featured-products") @include("shop::home.new-products")</p>
                        <div class="banner-container">
                        <div class="left-banner">
                            <img src='.asset("/themes/default/assets/images/1.webp").' data-src='.asset("/themes/default/assets/images/1.webp").' class="lazyload" alt="test" width="720" height="720" />
                        </div>
                        <div class="right-banner">
                            <img src='. asset("/themes/default/assets/images/2.webp").' data-src='. asset("/themes/default/assets/images/2.webp").' class="lazyload" alt="test" width="460" height="330" />
                            <img src='.asset("/themes/default/assets/images/3.webp").' data-src='.asset("/themes/default/assets/images/3.webp").'  class="lazyload" alt="test" width="460" height="330" />
                        </div>
                    </div>
                ',
                'footer_content'    => '
                    <div class="list-container">
                        <span class="list-heading">Quick Links</span>
                        <ul class="list-group">
                            <li><a href="'.route('shop.cms.page', 'about-us').'">About Us</a></li>
                            <li><a href="'.route('shop.cms.page', 'return-policy').'">Return Policy</a></li>
                            <li><a href="'.route('shop.cms.page', 'refund-policy') .'">Refund Policy</a></li>
                            <li><a href="'.route('shop.cms.page', 'terms-conditions').'">Terms and conditions</a></li>
                            <li><a href="'.route('shop.cms.page', 'terms-of-use').'">Terms of Use</a></li>
                            <li><a href="'.route('shop.cms.page', 'contact-us') .'">Contact Us</a></li>
                        </ul>
                    </div>
                    <div class="list-container">
                        <span class="list-heading">Connect With Us</span>
                            <ul class="list-group">
                                <li><a href="#"><span class="icon icon-facebook"></span>Facebook </a></li>
                                <li><a href="#"><span class="icon icon-twitter"></span> Twitter </a></li>
                                <li><a href="#"><span class="icon icon-instagram"></span> Instagram </a></li>
                                <li><a href="#"> <span class="icon icon-google-plus"></span>Google+ </a></li>
                                <li><a href="#"> <span class="icon icon-linkedin"></span>LinkedIn </a></li>
                            </ul>
                        </div>
                ',
                'home_seo'          => '{"meta_title": "Demo store", "meta_keywords": "Demo store meta keyword", "meta_description": "Demo store meta description"}',
            ], [
                'id'                => 3,
                'channel_id'        => 1,
                'locale'            => 'nl',
                'name'              => 'Default',
                'home_page_content' => '
                    <p>@include("shop::home.slider") @include("shop::home.featured-products") @include("shop::home.new-products")</p>
                        <div class="banner-container">
                        <div class="left-banner">
                            <img src='.asset("/themes/default/assets/images/1.webp").' data-src='.asset("/themes/default/assets/images/1.webp").' class="lazyload" alt="test" width="720" height="720" />
                        </div>
                        <div class="right-banner">
                            <img src='. asset("/themes/default/assets/images/2.webp").' data-src='. asset("/themes/default/assets/images/2.webp").' class="lazyload" alt="test" width="460" height="330" />
                            <img src='.asset("/themes/default/assets/images/3.webp").' data-src='.asset("/themes/default/assets/images/3.webp").'  class="lazyload" alt="test" width="460" height="330" />
                        </div>
                    </div>
                ',
                'footer_content'    => '
                    <div class="list-container">
                        <span class="list-heading">Quick Links</span>
                        <ul class="list-group">
                            <li><a href="'.route('shop.cms.page', 'about-us').'">About Us</a></li>
                            <li><a href="'.route('shop.cms.page', 'return-policy').'">Return Policy</a></li>
                            <li><a href="'.route('shop.cms.page', 'refund-policy') .'">Refund Policy</a></li>
                            <li><a href="'.route('shop.cms.page', 'terms-conditions').'">Terms and conditions</a></li>
                            <li><a href="'.route('shop.cms.page', 'terms-of-use').'">Terms of Use</a></li>
                            <li><a href="'.route('shop.cms.page', 'contact-us') .'">Contact Us</a></li>
                        </ul>
                    </div>
                    <div class="list-container">
                        <span class="list-heading">Connect With Us</span>
                            <ul class="list-group">
                                <li><a href="#"><span class="icon icon-facebook"></span>Facebook </a></li>
                                <li><a href="#"><span class="icon icon-twitter"></span> Twitter </a></li>
                                <li><a href="#"><span class="icon icon-instagram"></span> Instagram </a></li>
                                <li><a href="#"> <span class="icon icon-google-plus"></span>Google+ </a></li>
                                <li><a href="#"> <span class="icon icon-linkedin"></span>LinkedIn </a></li>
                            </ul>
                        </div>
                ',
                'home_seo'          => '{"meta_title": "Demo store", "meta_keywords": "Demo store meta keyword", "meta_description": "Demo store meta description"}',
            ], [
                'id'                => 4,
                'channel_id'        => 1,
                'locale'            => 'tr',
                'name'              => 'Default',
                'home_page_content' => '
                    <p>@include("shop::home.slider") @include("shop::home.featured-products") @include("shop::home.new-products")</p>
                        <div class="banner-container">
                        <div class="left-banner">
                            <img src='.asset("/themes/default/assets/images/1.webp").' data-src='.asset("/themes/default/assets/images/1.webp").' class="lazyload" alt="test" width="720" height="720" />
                        </div>
                        <div class="right-banner">
                            <img src='. asset("/themes/default/assets/images/2.webp").' data-src='. asset("/themes/default/assets/images/2.webp").' class="lazyload" alt="test" width="460" height="330" />
                            <img src='.asset("/themes/default/assets/images/3.webp").' data-src='.asset("/themes/default/assets/images/3.webp").'  class="lazyload" alt="test" width="460" height="330" />
                        </div>
                    </div>
                ',
                'footer_content'    => '
                    <div class="list-container">
                        <span class="list-heading">Quick Links</span>
                        <ul class="list-group">
                            <li><a href="'.route('shop.cms.page', 'about-us').'">About Us</a></li>
                            <li><a href="'.route('shop.cms.page', 'return-policy').'">Return Policy</a></li>
                            <li><a href="'.route('shop.cms.page', 'refund-policy') .'">Refund Policy</a></li>
                            <li><a href="'.route('shop.cms.page', 'terms-conditions').'">Terms and conditions</a></li>
                            <li><a href="'.route('shop.cms.page', 'terms-of-use').'">Terms of Use</a></li>
                            <li><a href="'.route('shop.cms.page', 'contact-us') .'">Contact Us</a></li>
                        </ul>
                    </div>
                    <div class="list-container">
                        <span class="list-heading">Connect With Us</span>
                            <ul class="list-group">
                                <li><a href="#"><span class="icon icon-facebook"></span>Facebook </a></li>
                                <li><a href="#"><span class="icon icon-twitter"></span> Twitter </a></li>
                                <li><a href="#"><span class="icon icon-instagram"></span> Instagram </a></li>
                                <li><a href="#"> <span class="icon icon-google-plus"></span>Google+ </a></li>
                                <li><a href="#"> <span class="icon icon-linkedin"></span>LinkedIn </a></li>
                            </ul>
                        </div>
                ',
                'home_seo'          => '{"meta_title": "Demo store", "meta_keywords": "Demo store meta keyword", "meta_description": "Demo store meta description"}',
            ], [
                'id'                => 5,
                'channel_id'        => 1,
                'locale'            => 'es',
                'name'              => 'Default',
                'home_page_content' => '
                    <p>@include("shop::home.slider") @include("shop::home.featured-products") @include("shop::home.new-products")</p>
                        <div class="banner-container">
                        <div class="left-banner">
                            <img src='.asset("/themes/default/assets/images/1.webp").' data-src='.asset("/themes/default/assets/images/1.webp").' class="lazyload" alt="test" width="720" height="720" />
                        </div>
                        <div class="right-banner">
                            <img src='. asset("/themes/default/assets/images/2.webp").' data-src='. asset("/themes/default/assets/images/2.webp").' class="lazyload" alt="test" width="460" height="330" />
                            <img src='.asset("/themes/default/assets/images/3.webp").' data-src='.asset("/themes/default/assets/images/3.webp").'  class="lazyload" alt="test" width="460" height="330" />
                        </div>
                    </div>
                ',
                'footer_content'    => '
                    <div class="list-container">
                        <span class="list-heading">Quick Links</span>
                        <ul class="list-group">
                            <li><a href="'.route('shop.cms.page', 'about-us').'">About Us</a></li>
                            <li><a href="'.route('shop.cms.page', 'return-policy').'">Return Policy</a></li>
                            <li><a href="'.route('shop.cms.page', 'refund-policy') .'">Refund Policy</a></li>
                            <li><a href="'.route('shop.cms.page', 'terms-conditions').'">Terms and conditions</a></li>
                            <li><a href="'.route('shop.cms.page', 'terms-of-use').'">Terms of Use</a></li>
                            <li><a href="'.route('shop.cms.page', 'contact-us') .'">Contact Us</a></li>
                        </ul>
                    </div>
                    <div class="list-container">
                        <span class="list-heading">Connect With Us</span>
                            <ul class="list-group">
                                <li><a href="#"><span class="icon icon-facebook"></span>Facebook </a></li>
                                <li><a href="#"><span class="icon icon-twitter"></span> Twitter </a></li>
                                <li><a href="#"><span class="icon icon-instagram"></span> Instagram </a></li>
                                <li><a href="#"> <span class="icon icon-google-plus"></span>Google+ </a></li>
                                <li><a href="#"> <span class="icon icon-linkedin"></span>LinkedIn </a></li>
                            </ul>
                        </div>
                ',
                'home_seo'          => '{"meta_title": "Demo store", "meta_keywords": "Demo store meta keyword", "meta_description": "Demo store meta description"}',
            ]
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
