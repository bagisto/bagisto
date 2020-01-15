<?php

namespace Webkul\CMS\Database\Seeders;

use Illuminate\Database\Seeder;
use DB;
use Carbon\Carbon;

class CMSPagesTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('cms_pages')->delete();

        DB::table('cms_pages')->insert([
            [
                'id' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ], [
                'id' => 2,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ], [
                'id' => 3,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ], [
                'id' => 4,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ], [
                'id' => 5,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ], [
                'id' => 6,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]
        ]);

        DB::table('cms_page_translations')->insert([
            [
                'locale' => 'en',
                'cms_page_id' => 1,
                'url_key' => 'about-us',
                'html_content' => '<div class="static-container">
                                   <div class="mb-5">About us page content</div>
                                   </div>',
                'page_title' => 'About Us',
                'meta_title' => 'about us',
                'meta_description' => '',
                'meta_keywords' => 'aboutus'
            ], [
                'locale' => 'en',
                'cms_page_id' => 2,
                'url_key' => 'return-policy',
                'html_content' => '<div class="static-container">
                                   <div class="mb-5">Return policy page content</div>
                                   </div>',
                'page_title' => 'Return Policy',
                'meta_title' => 'return policy',
                'meta_description' => '',
                'meta_keywords' => 'return, policy'
            ], [
                'locale' => 'en',
                'cms_page_id' => 3,
                'url_key' => 'refund-policy',
                'html_content' => '<div class="static-container">
                                   <div class="mb-5">Refund policy page content</div>
                                   </div>',
                'page_title' => 'Refund Policy',
                'meta_title' => 'Refund policy',
                'meta_description' => '',
                'meta_keywords' => 'refund, policy'
            ], [
                'locale' => 'en',
                'cms_page_id' => 4,
                'url_key' => 'terms-conditions',
                'html_content' => '<div class="static-container">
                                   <div class="mb-5">Terms & conditions page content</div>
                                   </div>',
                'page_title' => 'Terms & Conditions',
                'meta_title' => 'Terms & Conditions',
                'meta_description' => '',
                'meta_keywords' => 'term, conditions'
            ], [
                'locale' => 'en',
                'cms_page_id' => 5,
                'url_key' => 'terms-of-use',
                'html_content' => '<div class="static-container">
                                   <div class="mb-5">Terms of use page content</div>
                                   </div>',
                'page_title' => 'Terms of use',
                'meta_title' => 'Terms of use',
                'meta_description' => '',
                'meta_keywords' => 'term, use'
            ], [
                'locale' => 'en',
                'cms_page_id' => 6,
                'url_key' => 'contact-us',
                'html_content' => '<div class="static-container">
                                   <div class="mb-5">Contact us page content</div>
                                   </div>',
                'page_title' => 'Contact Us',
                'meta_title' => 'Contact Us',
                'meta_description' => '',
                'meta_keywords' => 'contact, us'
            ]
        ]);
    }
}