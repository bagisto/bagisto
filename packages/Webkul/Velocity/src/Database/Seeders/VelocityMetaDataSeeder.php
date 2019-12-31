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

                'footer_middle_content' => '<div class="col-4 footer-ct-content"><div class="row"><div class="col-6"><ul type="none"><li><a href="">About Us</a></li><li><a href="">Customer Service</a></li><li><a href="">What’s New</a></li><li><a href="">Contact Us </a></li></div><div class="col-6"><ul type="none"><li><a href="">Order and Returns</a></li><li><a href="">Payment Policy</a></li><li><a href="">Shipping Policy</a></li><li><a href="">Privacy and Cookies Policy</a></li></div></div></div>',

                'slider' => 1,

                'subscription_bar_content' => '<div class="social-icons wk-icon"><i class="fs24 within-circle rango-facebook" title="facebook"></i> <i class="fs24 within-circle rango-twitter" title="twitter"></i> <i class="fs24 within-circle rango-linked-in" title="linkedin"></i> <i class="fs24 within-circle rango-pintrest" title="Pinterest"></i> <i class="fs24 within-circle rango-youtube" title="Youtube"></i> <i class="fs24 within-circle rango-instagram" title="instagram"></i></div>'
            ]);
    }
}
