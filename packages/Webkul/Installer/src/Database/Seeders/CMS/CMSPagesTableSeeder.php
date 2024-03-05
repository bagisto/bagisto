<?php

namespace Webkul\Installer\Database\Seeders\CMS;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CMSPagesTableSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @param  array  $parameters
     * @return void
     */
    public function run($parameters = [])
    {
        DB::table('cms_pages')->delete();

        DB::table('cms_page_translations')->delete();

        $defaultLocale = $parameters['default_locale'] ?? config('app.locale');

        DB::table('cms_pages')->insert([
            [
                'id'         => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ], [
                'id'         => 2,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ], [
                'id'         => 3,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ], [
                'id'         => 4,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ], [
                'id'         => 5,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ], [
                'id'         => 6,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ], [
                'id'         => 7,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ], [
                'id'         => 8,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ], [
                'id'         => 9,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ], [
                'id'         => 10,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ], [
                'id'         => 11,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);

        $locales = $parameters['allowed_locales'] ?? [$defaultLocale];

        foreach ($locales as $locale) {
            DB::table('cms_page_translations')->insert([
                [
                    'locale'           => $locale,
                    'cms_page_id'      => 1,
                    'url_key'          => 'about-us',
                    'html_content'     => '<div class="static-container"><div class="mb-5">'.trans('installer::app.seeders.cms.pages.about-us.content', [], $locale).'</div></div>',
                    'page_title'       => trans('installer::app.seeders.cms.pages.about-us.title', [], $locale),
                    'meta_title'       => 'about us',
                    'meta_description' => '',
                    'meta_keywords'    => 'aboutus',
                ], [
                    'locale'           => $locale,
                    'cms_page_id'      => 2,
                    'url_key'          => 'return-policy',
                    'html_content'     => '<div class="static-container"><div class="mb-5">'.trans('installer::app.seeders.cms.pages.return-policy.content', [], $locale).'</div></div>',
                    'page_title'       => trans('installer::app.seeders.cms.pages.return-policy.title', [], $locale),
                    'meta_title'       => 'return policy',
                    'meta_description' => '',
                    'meta_keywords'    => 'return, policy',
                ], [
                    'locale'           => $locale,
                    'cms_page_id'      => 3,
                    'url_key'          => 'refund-policy',
                    'html_content'     => '<div class="static-container"><div class="mb-5">'.trans('installer::app.seeders.cms.pages.refund-policy.content', [], $locale).'</div></div>',
                    'page_title'       => trans('installer::app.seeders.cms.pages.refund-policy.title', [], $locale),
                    'meta_title'       => 'Refund policy',
                    'meta_description' => '',
                    'meta_keywords'    => 'refund, policy',
                ], [
                    'locale'           => $locale,
                    'cms_page_id'      => 4,
                    'url_key'          => 'terms-conditions',
                    'html_content'     => '<div class="static-container"><div class="mb-5">'.trans('installer::app.seeders.cms.pages.terms-conditions.content', [], $locale).'</div></div>',
                    'page_title'       => trans('installer::app.seeders.cms.pages.terms-conditions.title', [], $locale),
                    'meta_title'       => 'Terms & Conditions',
                    'meta_description' => '',
                    'meta_keywords'    => 'term, conditions',
                ], [
                    'locale'           => $locale,
                    'cms_page_id'      => 5,
                    'url_key'          => 'terms-of-use',
                    'html_content'     => '<div class="static-container"><div class="mb-5">'.trans('installer::app.seeders.cms.pages.terms-of-use.content', [], $locale).'</div></div>',
                    'page_title'       => trans('installer::app.seeders.cms.pages.terms-of-use.title', [], $locale),
                    'meta_title'       => 'Terms of use',
                    'meta_description' => '',
                    'meta_keywords'    => 'term, use',
                ], [
                    'locale'           => $locale,
                    'cms_page_id'      => 6,
                    'url_key'          => 'contact-us',
                    'html_content'     => '<div class="static-container"><div class="mb-5">'.trans('installer::app.seeders.cms.pages.contact-us.content', [], $locale).'</div></div>',
                    'page_title'       => trans('installer::app.seeders.cms.pages.contact-us.title', [], $locale),
                    'meta_title'       => 'Contact Us',
                    'meta_description' => '',
                    'meta_keywords'    => 'contact, us',
                ], [
                    'locale'           => $locale,
                    'cms_page_id'      => 7,
                    'url_key'          => 'customer-service',
                    'html_content'     => '<div class="static-container"><div class="mb-5">'.trans('installer::app.seeders.cms.pages.customer-service.content', [], $locale).'</div></div>',
                    'page_title'       => trans('installer::app.seeders.cms.pages.customer-service.title', [], $locale),
                    'meta_title'       => 'Customer Service',
                    'meta_description' => '',
                    'meta_keywords'    => 'customer, service',
                ], [
                    'locale'           => $locale,
                    'cms_page_id'      => 8,
                    'url_key'          => 'whats-new',
                    'html_content'     => '<div class="static-container"><div class="mb-5">'.trans('installer::app.seeders.cms.pages.whats-new.content', [], $locale).'</div></div>',
                    'page_title'       => trans('installer::app.seeders.cms.pages.whats-new.title', [], $locale),
                    'meta_title'       => 'What\'s New',
                    'meta_description' => '',
                    'meta_keywords'    => 'new',
                ], [
                    'locale'           => $locale,
                    'cms_page_id'      => 9,
                    'url_key'          => 'payment-policy',
                    'html_content'     => '<div class="static-container"><div class="mb-5">'.trans('installer::app.seeders.cms.pages.payment-policy.content', [], $locale).'</div></div>',
                    'page_title'       => trans('installer::app.seeders.cms.pages.payment-policy.title', [], $locale),
                    'meta_title'       => 'Payment Policy',
                    'meta_description' => '',
                    'meta_keywords'    => 'payment, policy',
                ], [
                    'locale'           => $locale,
                    'cms_page_id'      => 10,
                    'url_key'          => 'shipping-policy',
                    'html_content'     => '<div class="static-container"><div class="mb-5">'.trans('installer::app.seeders.cms.pages.shipping-policy.content', [], $locale).'</div></div>',
                    'page_title'       => trans('installer::app.seeders.cms.pages.shipping-policy.title', [], $locale),
                    'meta_title'       => 'Shipping Policy',
                    'meta_description' => '',
                    'meta_keywords'    => 'shipping, policy',
                ], [
                    'locale'           => $locale,
                    'cms_page_id'      => 11,
                    'url_key'          => 'privacy-policy',
                    'html_content'     => '<div class="static-container"><div class="mb-5">'.trans('installer::app.seeders.cms.pages.privacy-policy.content', [], $locale).'</div></div>',
                    'page_title'       => trans('installer::app.seeders.cms.pages.privacy-policy.title', [], $locale),
                    'meta_title'       => 'Privacy Policy',
                    'meta_description' => '',
                    'meta_keywords'    => 'privacy, policy',
                ],
            ]);
        }
    }
}
