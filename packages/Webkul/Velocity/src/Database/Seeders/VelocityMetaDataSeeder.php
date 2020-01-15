<?php

namespace Webkul\Velocity\Database\Seeders;

use DB;
use Illuminate\Database\Seeder;

class VelocityMetaDataSeeder extends Seeder
{
    public function run()
    {
        DB::table('velocity_meta_data')->delete();

        DB::table('velocity_meta_data')->insert([
                'id' => 1,

                'home_page_content' => "<p>@include('shop::home.advertisements.advertisement-one')@include('shop::home.featured-products') @include('shop::home.advertisements.advertisement-two') @include('shop::home.new-products') @include('shop::home.advertisements.advertisement-three')</p>",

                'footer_left_content' => trans('velocity::app.admin.meta-data.footer-left-raw-content'),

                'footer_middle_content' => '<div class="col-lg-6 col-md-12 col-sm-12 no-padding"><ul type="none"><li><a href="https://webkul.com/about-us/company-profile/">About Us</a></li><li><a href="https://webkul.com/about-us/company-profile/">Customer Service</a></li><li><a href="https://webkul.com/about-us/company-profile/">What&rsquo;s New</a></li><li><a href="https://webkul.com/about-us/company-profile/">Contact Us </a></li></ul></div><div class="col-lg-6 col-md-12 col-sm-12 no-padding"><ul type="none"><li><a href="https://webkul.com/about-us/company-profile/"> Order and Returns </a></li><li><a href="https://webkul.com/about-us/company-profile/"> Payment Policy </a></li><li><a href="https://webkul.com/about-us/company-profile/"> Shipping Policy</a></li><li><a href="https://webkul.com/about-us/company-profile/"> Privacy and Cookies Policy </a></li></ul></div>',

                'slider' => 1,

                'subscription_bar_content' => '<div class="social-icons col-lg-6"><i class="fs24 within-circle rango-facebook" title="facebook"></i> <i class="fs24 within-circle rango-twitter" title="twitter"></i> <i class="fs24 within-circle rango-linked-in" title="linkedin"></i> <i class="fs24 within-circle rango-pintrest" title="Pinterest"></i> <i class="fs24 within-circle rango-youtube" title="Youtube"></i> <i class="fs24 within-circle rango-instagram" title="instagram"></i></div>',

                'product_policy' => '<div class="col-sm-4 col-xs-12 product-policy-wrapper"><div class="card"><div class="row"><div class="col-sm-2"><i class="rango-van-ship fs40"></i></div><div class="col-sm-10"><span class="font-setting fs20">Free Shippingon Order $20 or More</span></div></div></div></div><div class="col-sm-4 col-xs-12 product-policy-wrapper"><div class="card"><div class="row"><div class="col-sm-2"><i class="rango-exchnage fs40"></i></div><div class="col-sm-10"><span class="font-setting fs20">ProductReplace &amp; Return Available </span></div></div></div></div><div class="col-sm-4 col-xs-12 product-policy-wrapper"><div class="card"><div class="row"><div class="col-sm-2"><i class="rango-exchnage fs40"></i></div><div class="col-sm-10"><span class="font-setting fs20">ProductExchange and EMI Available </span></div></div></div></div>',
            ]);
    }
}
