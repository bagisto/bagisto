<?php

namespace Webkul\Installer\Database\Seeders\Shop;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ThemeCustomizationTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('theme_customizations')->delete();

        DB::table('theme_customization_translations')->delete();

        $now = Carbon::now();

        DB::table('theme_customizations')
            ->insert([
                [
                    'id'         => 1,
                    'type'       => 'image_carousel',
                    'name'       => trans('installer::app.seeders.shop.theme-customizations.image-carousel.name'),
                    'sort_order' => 1,
                    'status'     => 1,
                    'created_at' => $now,
                    'updated_at' => $now,
                ], [
                    'id'         => 2,
                    'type'       => 'static_content',
                    'name'       => trans('installer::app.seeders.shop.theme-customizations.offer-information.name'),
                    'sort_order' => 2,
                    'status'     => 1,
                    'created_at' => $now,
                    'updated_at' => $now,
                ], [
                    'id'         => 3,
                    'type'       => 'category_carousel',
                    'name'       => trans('installer::app.seeders.shop.theme-customizations.categories-collections.name'),
                    'sort_order' => 3,
                    'status'     => 1,
                    'created_at' => $now,
                    'updated_at' => $now,
                ], [
                    'id'         => 4,
                    'type'       => 'product_carousel',
                    'name'       => trans('installer::app.seeders.shop.theme-customizations.new-products.name'),
                    'sort_order' => 4,
                    'status'     => 1,
                    'created_at' => $now,
                    'updated_at' => $now,
                ], [
                    'id'         => 5,
                    'type'       => 'static_content',
                    'name'       => trans('installer::app.seeders.shop.theme-customizations.top-collections.name'),
                    'sort_order' => 5,
                    'status'     => 1,
                    'created_at' => $now,
                    'updated_at' => $now,
                ], [
                    'id'         => 6,
                    'type'       => 'static_content',
                    'name'       => trans('installer::app.seeders.shop.theme-customizations.bold-collections.name'),
                    'sort_order' => 6,
                    'status'     => 1,
                    'created_at' => $now,
                    'updated_at' => $now,
                ], [
                    'id'         => 7,
                    'type'       => 'product_carousel',
                    'name'       => trans('installer::app.seeders.shop.theme-customizations.featured-collections.name'),
                    'sort_order' => 7,
                    'status'     => 1,
                    'created_at' => $now,
                    'updated_at' => $now,
                ], [
                    'id'         => 8,
                    'type'       => 'static_content',
                    'name'       => trans('installer::app.seeders.shop.theme-customizations.game-container.name'),
                    'sort_order' => 8,
                    'status'     => 1,
                    'created_at' => $now,
                    'updated_at' => $now,
                ], [
                    'id'         => 9,
                    'type'       => 'product_carousel',
                    'name'       => trans('installer::app.seeders.shop.theme-customizations.all-products.name'),
                    'sort_order' => 9,
                    'status'     => 1,
                    'created_at' => $now,
                    'updated_at' => $now,
                ], [
                    'id'         => 10,
                    'type'       => 'static_content',
                    'name'       => trans('installer::app.seeders.shop.theme-customizations.bold-collections.name'),
                    'sort_order' => 10,
                    'status'     => 1,
                    'created_at' => $now,
                    'updated_at' => $now,
                ], [
                    'id'         => 11,
                    'type'       => 'footer_links',
                    'name'       => trans('installer::app.seeders.shop.theme-customizations.footer-links.name'),
                    'sort_order' => 11,
                    'status'     => 1,
                    'created_at' => $now,
                    'updated_at' => $now,
                ],
            ]);

        DB::table('theme_customization_translations')
            ->insert([
                /**
                 * Customizations for English locale
                 */
                [
                    'theme_customization_id' => 1,
                    'locale'                 => config('app.locale'),
                    'options'                => json_encode([
                        'images' => [
                            [
                                'title' => trans('installer::app.seeders.shop.theme-customizations.image-carousel.sliders.title'),
                                'link'  => '',
                                'image' => 'storage/theme/1/1.webp',
                            ], [
                                'title' => trans('installer::app.seeders.shop.theme-customizations.image-carousel.sliders.title'),
                                'link'  => '',
                                'image' => 'storage/theme/1/2.webp',
                            ], [
                                'title' => trans('installer::app.seeders.shop.theme-customizations.image-carousel.sliders.title'),
                                'link'  => '',
                                'image' => 'storage/theme/1/3.webp',
                            ], [
                                'title' => trans('installer::app.seeders.shop.theme-customizations.image-carousel.sliders.title'),
                                'link'  => '',
                                'image' => 'storage/theme/1/4.webp',
                            ],
                        ],
                    ]),
                ], [
                    'theme_customization_id' => 2,
                    'locale'                 => config('app.locale'),
                    'options'                => json_encode([
                        'html' => '<div class="home-offer"><h1>' . trans('installer::app.seeders.shop.theme-customizations.offer-information.content.title') . '</h1></div>',
                        'css'  => '.home-offer h1 {display: block;font-weight: 500;text-align: center;font-size: 22px;font-family: DM Serif Display;background-color: #E8EDFE;padding-top: 20px;padding-bottom: 20px;}@media (max-width:768px){.home-offer h1 {font-size:18px;}@media (max-width:525px) {.home-offer h1 {font-size:14px;}}',
                    ]),
                ], [
                    'theme_customization_id' => 3,
                    'locale'                 => config('app.locale'),
                    'options'                => json_encode([
                        'filters' => [
                            'parent_id'  => 1,
                            'sort'       => 'asc',
                            'limit'      => 10,
                        ],
                    ]),
                ], [
                    'theme_customization_id' => 4,
                    'locale'                 => config('app.locale'),
                    'options'                => json_encode([
                        'title'   => trans('installer::app.seeders.shop.theme-customizations.new-products.options.title'),
                        'filters' => [
                            'new'   => 1,
                            'sort'  => 'asc',
                            'limit' => 10,
                        ],
                    ]),
                ], [
                    'theme_customization_id' => 5,
                    'locale'                 => config('app.locale'),
                    'options'                => json_encode([
                        'html' => '<div class="top-collection-container"><div class="top-collection-header"><h2>' . trans('installer::app.seeders.shop.theme-customizations.top-collections.content.title') . '</h2></div><div class="container top-collection-grid"><div class="top-collection-card"><img src="" data-src="storage/theme/5/1.webp" class="lazy" width="396" height="396" alt="' . trans('installer::app.seeders.shop.theme-customizations.top-collections.content.title') . '"><h3>' . trans('installer::app.seeders.shop.theme-customizations.top-collections.content.sub-title-1') . '</h3></div><div class="top-collection-card"><img src="" data-src="storage/theme/5/2.webp" class="lazy" width="396" height="396" alt="' . trans('installer::app.seeders.shop.theme-customizations.top-collections.content.title') . '"><h3>' . trans('installer::app.seeders.shop.theme-customizations.top-collections.content.sub-title-2') . '</h3></div><div class="top-collection-card"><img src="" data-src="storage/theme/5/1.webp" class="lazy" width="396" height="396" alt="' . trans('installer::app.seeders.shop.theme-customizations.top-collections.content.title') . '"><h3>' . trans('installer::app.seeders.shop.theme-customizations.top-collections.content.sub-title-3') . '</h3></div><div class="top-collection-card"><img src="" data-src="storage/theme/5/2.webp" class="lazy" width="396" height="396" alt="' . trans('installer::app.seeders.shop.theme-customizations.top-collections.content.title') . '"><h3>' . trans('installer::app.seeders.shop.theme-customizations.top-collections.content.sub-title-4') . '</h3></div><div class="top-collection-card"><img src="" data-src="storage/theme/5/1.webp" class="lazy" width="396" height="396" alt="' . trans('installer::app.seeders.shop.theme-customizations.top-collections.content.title') . '"><h3>' . trans('installer::app.seeders.shop.theme-customizations.top-collections.content.sub-title-5') . '</h3></div><div class="top-collection-card"><img src="" data-src="storage/theme/5/2.webp" class="lazy" width="396" height="396" alt="' . trans('installer::app.seeders.shop.theme-customizations.top-collections.content.title') . '"><h3>' . trans('installer::app.seeders.shop.theme-customizations.top-collections.content.sub-title-6') . '</h3></div></div></div>',

                        'css'  => '.top-collection-header {padding-left: 15px;padding-right: 15px;text-align: center;font-size: 70px;line-height: 90px;color: #060C3B;margin-top: 80px;}.top-collection-header h2 {max-width: 595px;margin-left: auto;margin-right: auto;font-family: DM Serif Display;}.top-collection-grid {display: flex;flex-wrap: wrap;gap: 32px;justify-content: center;margin-top: 60px;width: 100%;margin-right: auto;margin-left: auto;padding-right: 90px;padding-left: 90px;}.top-collection-card {position: relative;background: #f9fafb;}.top-collection-card img {border-radius: 16px;max-width: 100%;text-indent:-9999px;}.top-collection-card h3 {color: #060C3B;font-size: 30px;font-family: DM Serif Display;transform: translateX(-50%);width: max-content;left: 50%;bottom: 30px;position: absolute;margin: 0;font-weight: inherit;}@media not all and (min-width: 525px) {.top-collection-header {margin-top: 30px;}.top-collection-header {font-size: 32px;line-height: 1.5;}.top-collection-grid {gap: 15px;}}@media not all and (min-width: 1024px) {.top-collection-grid {padding-left: 30px;padding-right: 30px;}}@media (max-width: 640px) {.top-collection-grid {margin-top: 20px;}}',
                    ]),
                ], [
                    'theme_customization_id' => 6,
                    'locale'                 => config('app.locale'),
                    'options'                => json_encode([
                        'html' => '<div class="container section-gap"> <div class="inline-col-wrapper"> <div class="inline-col-image-wrapper"> <img src="" data-src="storage/theme/6/1.webp" class="lazy" width="632" height="510" alt="' . trans('installer::app.seeders.shop.theme-customizations.bold-collections.content.title') . '"> </div> <div class="inline-col-content-wrapper"> <h2 class="inline-col-title"> ' . trans('installer::app.seeders.shop.theme-customizations.bold-collections.content.title') . ' </h2> <p class="inline-col-description">' . trans('installer::app.seeders.shop.theme-customizations.bold-collections.content.description') . '</p> <button class="primary-button">' . trans('installer::app.seeders.shop.theme-customizations.bold-collections.content.btn-title') . '</button> </div> </div> </div>',

                        'css'  => '.section-gap{margin-top:80px}.direction-ltr{direction:ltr}.direction-rtl{direction:rtl}.inline-col-wrapper{display:grid;grid-template-columns:auto 1fr;grid-gap:60px;align-items:center}.inline-col-wrapper .inline-col-image-wrapper{overflow:hidden}.inline-col-wrapper .inline-col-image-wrapper img{max-width:100%;height:auto;border-radius:16px;text-indent:-9999px}.inline-col-wrapper .inline-col-content-wrapper{display:flex;flex-wrap:wrap;gap:20px;max-width:464px}.inline-col-wrapper .inline-col-content-wrapper .inline-col-title{max-width:442px;font-size:60px;font-weight:400;color:#060c3b;line-height:70px;font-family:DM Serif Display;margin:0}.inline-col-wrapper .inline-col-content-wrapper .inline-col-description{margin:0;font-size:18px;color:#6e6e6e;font-family:Poppins}@media (max-width:991px){.inline-col-wrapper{grid-template-columns:1fr;grid-gap:16px}.inline-col-wrapper .inline-col-content-wrapper{gap:10px}}@media (max-width:525px){.inline-col-wrapper .inline-col-content-wrapper .inline-col-title{font-size:30px;line-height:normal}}',
                    ]),
                ], [
                    'theme_customization_id' => 7,
                    'locale'                 => config('app.locale'),
                    'options'                => json_encode([
                        'title'   => trans('installer::app.seeders.shop.theme-customizations.featured-collections.options.title'),
                        'filters' => [
                            'featured' => 1,
                            'sort'     => 'desc',
                            'limit'    => 10,
                        ],
                    ]),
                ], [
                    'theme_customization_id' => 8,
                    'locale'                 => config('app.locale'),
                    'options'                => json_encode([
                        'html' => '<div class="section-title"> <h2>' . trans('installer::app.seeders.shop.theme-customizations.game-container.content.title') . '</h2> </div> <div class="container section-gap"> <div class="collection-card-wrapper"> <div class="single-collection-card"> <img src="" data-src="storage/theme/8/1.webp" class="lazy" width="615" height="600" alt="' . trans('installer::app.seeders.shop.theme-customizations.game-container.content.title') . '"> <h3 class="overlay-text">' . trans('installer::app.seeders.shop.theme-customizations.game-container.content.sub-title-1') . '</h3> </div> <div class="single-collection-card"> <img src="" data-src="storage/theme/8/2.webp" class="lazy" width="615" height="600" alt="' . trans('installer::app.seeders.shop.theme-customizations.game-container.content.title') . '"> <h3 class="overlay-text"> ' . trans('installer::app.seeders.shop.theme-customizations.game-container.content.sub-title-2') . ' </h3> </div> </div> </div>',

                        'css'  => '.section-title,.section-title h2{font-weight:400;font-family:DM Serif Display}.section-title{margin-top:80px;padding-left:15px;padding-right:15px;text-align:center;line-height:90px}.section-title h2{font-size:70px;color:#060c3b;max-width:595px;margin:auto}.collection-card-wrapper{display:flex;flex-wrap:wrap;justify-content:center;gap:30px}.collection-card-wrapper .single-collection-card{position:relative}.collection-card-wrapper .single-collection-card img{border-radius:16px;background-color:#f5f5f5;max-width:100%;height:auto;text-indent:-9999px}.collection-card-wrapper .single-collection-card .overlay-text{font-size:50px;font-weight:400;max-width:234px;font-style:italic;color:#060c3b;font-family:DM Serif Display;position:absolute;bottom:30px;left:30px;margin:0}@media (max-width:1024px){.section-title{padding:0 30px}}@media (max-width:991px){.collection-card-wrapper{flex-wrap:wrap}}@media (max-width:525px){.collection-card-wrapper .single-collection-card img{max-width:calc(100vw - 30px)}.collection-card-wrapper .single-collection-card .overlay-text{font-size:30px}.container{padding:0 30px;margin-top:20px}.section-title{margin-top:30px}.section-title h2{font-size:30px;line-height:normal}}',
                    ]),
                ], [
                    'theme_customization_id' => 9,
                    'locale'                 => config('app.locale'),
                    'options'                => json_encode([
                        'title'   => trans('installer::app.seeders.shop.theme-customizations.all-products.options.title'),
                        'filters' => [
                            'sort'  => 'desc',
                            'limit' => 10,
                        ],
                    ]),
                ], [
                    'theme_customization_id' => 10,
                    'locale'                 => config('app.locale'),
                    'options'                => json_encode([
                        'html' => '<div class="container section-gap"> <div class="inline-col-wrapper direction-rtl"> <div class="inline-col-image-wrapper "> <img src="" data-src="storage/theme/10/1.webp" class="lazy" width="632" height="510" alt="' . trans('installer::app.seeders.shop.theme-customizations.bold-collections.content.title') . '"> </div> <div class="inline-col-content-wrapper direction-ltr"> <h2 class="inline-col-title"> ' . trans('installer::app.seeders.shop.theme-customizations.bold-collections.content.title') . ' </h2> <p class="inline-col-description">' . trans('installer::app.seeders.shop.theme-customizations.bold-collections.content.description') . '</p> <button class="primary-button">' . trans('installer::app.seeders.shop.theme-customizations.bold-collections.content.btn-title') . '</button> </div> </div> </div>',

                        'css'  => '.section-gap{margin-top:80px}.direction-ltr{direction:ltr}.direction-rtl{direction:rtl}.inline-col-wrapper{display:grid;grid-template-columns:auto 1fr;grid-gap:60px;align-items:center}.inline-col-wrapper .inline-col-image-wrapper{overflow:hidden}.inline-col-wrapper .inline-col-image-wrapper img{max-width:100%;height:auto;border-radius:16px;text-indent:-9999px}.inline-col-wrapper .inline-col-content-wrapper{display:flex;flex-wrap:wrap;gap:20px;max-width:464px}.inline-col-wrapper .inline-col-content-wrapper .inline-col-title{max-width:442px;font-size:60px;font-weight:400;color:#060c3b;line-height:70px;font-family:DM Serif Display;margin:0}.inline-col-wrapper .inline-col-content-wrapper .inline-col-description{margin:0;font-size:18px;color:#6e6e6e;font-family:Poppins}@media (max-width:991px){.inline-col-wrapper{grid-template-columns:1fr;grid-gap:16px}.inline-col-wrapper .inline-col-content-wrapper{gap:10px}}@media (max-width:525px){.inline-col-wrapper .inline-col-content-wrapper .inline-col-title{font-size:30px;line-height:normal}}',
                    ]),
                ], [
                    'theme_customization_id' => 11,
                    'locale'                 => config('app.locale'),
                    'options'                => json_encode([
                        'column_1' => [
                            [
                                'url'        => 'http://localhost/page/about-us',
                                'title'      => trans('installer::app.seeders.shop.theme-customizations.footer-links.options.about-us'),
                                'sort_order' => 1,
                            ], [
                                'url'        => 'http://localhost/page/contact-us',
                                'title'      => trans('installer::app.seeders.shop.theme-customizations.footer-links.options.contact-us'),
                                'sort_order' => 2,
                            ], [
                                'url'        => 'http://localhost/page/customer-service',
                                'title'      => trans('installer::app.seeders.shop.theme-customizations.footer-links.options.customer-service'),
                                'sort_order' => 3,
                            ], [
                                'url'        => 'http://localhost/page/whats-new',
                                'title'      => trans('installer::app.seeders.shop.theme-customizations.footer-links.options.whats-new'),
                                'sort_order' => 4,
                            ], [
                                'url'        => 'http://localhost/page/terms-of-use',
                                'title'      => trans('installer::app.seeders.shop.theme-customizations.footer-links.options.terms-of-use'),
                                'sort_order' => 5,
                            ], [
                                'url'        => 'http://localhost/page/terms-conditions',
                                'title'      => trans('installer::app.seeders.shop.theme-customizations.footer-links.options.terms-conditions'),
                                'sort_order' => 6,
                            ],
                        ],

                        'column_2' => [
                            [
                                'url'        => 'http://localhost/page/privacy-policy',
                                'title'      => trans('installer::app.seeders.shop.theme-customizations.footer-links.options.privacy-policy'),
                                'sort_order' => 1,
                            ], [
                                'url'        => 'http://localhost/page/payment-policy',
                                'title'      => trans('installer::app.seeders.shop.theme-customizations.footer-links.options.payment-policy'),
                                'sort_order' => 2,
                            ], [
                                'url'        => 'http://localhost/page/shipping-policy',
                                'title'      => trans('installer::app.seeders.shop.theme-customizations.footer-links.options.shipping-policy'),
                                'sort_order' => 3,
                            ], [
                                'url'        => 'http://localhost/page/refund-policy',
                                'title'      => trans('installer::app.seeders.shop.theme-customizations.footer-links.options.refund-policy'),
                                'sort_order' => 4,
                            ], [
                                'url'        => 'http://localhost/page/return-policy',
                                'title'      => trans('installer::app.seeders.shop.theme-customizations.footer-links.options.return-policy'),
                                'sort_order' => 5,
                            ],
                        ],
                    ]),
                ],
            ]);
    }
}
