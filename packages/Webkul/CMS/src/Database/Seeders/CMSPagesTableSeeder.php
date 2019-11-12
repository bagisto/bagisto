<?php

namespace Webkul\CMS\Database\Seeders;

use Illuminate\Database\Seeder;
use DB;

class CMSPagesTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('cms_pages')->delete();

        DB::table('cms_pages')->insert([
            [
                'id' => '1',
                'url_key' => 'about-us',
                'html_content' => '<div class="static-container one-column">
                                   <div class="mb-5">About us page content</div>
                                   </div>',
                'page_title' => 'About Us',
                'meta_title' => 'about us',
                'meta_description' => '',
                'meta_keywords' => 'aboutus',
                'content' => '{"html": "<div class=\"static-container one-column\">\r\n<div class=\"mb-5\">About us page content</div>\r\n</div>",
                            "meta_title": "about us",
                            "page_title": "About Us",
                            "meta_keywords": "aboutus ", "meta_description": ""}',
                'channel_id' => 1,
                'locale_id' => 1
            ], [
                'id' => '2',
                'url_key' => 'return-policy',
                'html_content' => '<div class="static-container one-column">
                                   <div class="mb-5">Return policy page content</div>
                                   </div>',
                'page_title' => 'Return Policy',
                'meta_title' => 'return policy',
                'meta_description' => '',
                'meta_keywords' => 'return, policy',
                'content' => '{"html": "<div class=\"static-container one-column\">\r\n<div class=\"mb-5\">Return policy page content</div>\r\n</div>",
                            "meta_title": "return policy",
                            "page_title": "Return Policy",
                            "meta_keywords": "return, policy ", "meta_description": ""}',
                'channel_id' => 1,
                'locale_id' => 1
            ], [
                'id' => '3',
                'url_key' => 'refund-policy',
                'html_content' => '<div class="static-container one-column">
                                   <div class="mb-5">Refund policy page content</div>
                                   </div>',
                'page_title' => 'Refund Policy',
                'meta_title' => 'Refund policy',
                'meta_description' => '',
                'meta_keywords' => 'refund, policy',
                'content' => '{"html": "<div class=\"static-container one-column\">\r\n<div class=\"mb-5\">Refund policy page content</div>\r\n</div>",
                            "meta_title": "Refund policy",
                            "page_title": "Refund Policy",
                            "meta_keywords": "refund,policy ", "meta_description": ""}',
                'channel_id' => 1,
                'locale_id' => 1
            ], [
                'id' => '4',
                'url_key' => 'terms-conditions',
                'html_content' => '<div class="static-container one-column">
                                   <div class="mb-5">Terms & conditions page content</div>
                                   </div>',
                'page_title' => 'Terms & Conditions',
                'meta_title' => 'Terms & Conditions',
                'meta_description' => '',
                'meta_keywords' => 'term, conditions',
                'content' => '{"html": "<div class=\"static-container one-column\">\r\n<div class=\"mb-5\">Terms & conditions page content</div>\r\n</div>",
                            "meta_title": "Terms & Conditions",
                            "page_title": "Terms & Conditions",
                            "meta_keywords": "terms, conditions ", "meta_description": ""}',
                'channel_id' => 1,
                'locale_id' => 1
            ], [
                'id' => '5',
                'url_key' => 'terms-of-use',
                'html_content' => '<div class="static-container one-column">
                                   <div class="mb-5">Terms of use page content</div>
                                   </div>',
                'page_title' => 'Terms of use',
                'meta_title' => 'Terms of use',
                'meta_description' => '',
                'meta_keywords' => 'term, use',
                'content' => '{"html": "<div class=\"static-container one-column\">\r\n<div class=\"mb-5\">Terms of use page content</div>\r\n</div>",
                            "meta_title": "Terms of use",
                            "page_title": "Terms of use",
                            "meta_keywords": "terms, use ", "meta_description": ""}',
                'channel_id' => 1,
                'locale_id' => 1
            ], [
                'id' => '6',
                'url_key' => 'contact-us',
                'html_content' => '<div class="static-container one-column">
                                   <div class="mb-5">Contact us page content</div>
                                   </div>',
                'page_title' => 'Contact Us',
                'meta_title' => 'Contact Us',
                'meta_description' => '',
                'meta_keywords' => 'contact, us',
                'content' => '{"html": "<div class=\"static-container one-column\">\r\n<div class=\"mb-5\">Contact us page content</div>\r\n</div>",
                            "meta_title": "Contact Us",
                            "page_title": "Contact Us",
                            "meta_keywords": "contact, us ", "meta_description": ""}',
                'channel_id' => 1,
                'locale_id' => 1
            ]
        ]);
    }
}