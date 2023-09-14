<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('theme_customizations', function (Blueprint $table) {
            $table->id();
            $table->string('type');
            $table->string('name');
            $table->json('options');
            $table->integer('sort_order');
            $table->boolean('status')->default(0);
            $table->timestamps();
        });

        $now = Carbon::now();

        DB::table('theme_customizations')
            ->insert([
                [
                    'id'         => 1,
                    'type'       => 'image_carousel',
                    'name'       => 'Image Carousel',
                    'sort_order' => 1,
                    'status'     => 1,
                    'created_at' => $now,
                    'updated_at' => $now,
                    'options'    => json_encode([
                        'images' => [
                            [
                                'link'  => '',
                                'image' => 'storage/theme/1/1.webp',
                            ], [
                                'link'  => '',
                                'image' => 'storage/theme/1/2.webp',
                            ], [
                                'link'  => '',
                                'image' => 'storage/theme/1/3.webp',
                            ], [
                                'link'  => '',
                                'image' => 'storage/theme/1/4.webp',
                            ],
                        ],
                    ]),
                ], [
                    'id'         => 2,
                    'type'       => 'static_content',
                    'name'       => 'Offer Information',
                    'sort_order' => 2,
                    'status'     => 1,
                    'created_at' => $now,
                    'updated_at' => $now,
                    'options'    => json_encode([
                        'html' => '<div class="home-offer"><a href="javascript:void(0);">Get UPTO 40% OFF on your 1st order SHOP NOW</a></div>',
                        'css'  => '.home-offer a {display: block;font-weight: 500;text-align: center;font-size: 22px;font-family: DM Serif Display;background-color: #E8EDFE;padding-top: 20px;padding-bottom: 20px;}@media (max-width:768px){.home-offer a {font-size:18px;}@media (max-width:525px) {.home-offer a {font-size:14px;}}'
                    ]),
                ], [
                    'id'         => 3,
                    'type'       => 'category_carousel',
                    'name'       => 'Categories Collections',
                    'sort_order' => 3,
                    'status'     => 1,
                    'created_at' => $now,
                    'updated_at' => $now,
                    'options'    => json_encode([
                        'filters' => [
                            'parent_id'  => 1,
                            'sort'      => 'asc',
                            'limit'     => 10,
                        ],
                    ]),
                ], [
                    'id'         => 4,
                    'type'       => 'product_carousel',
                    'name'       => 'New Products',
                    'sort_order' => 4,
                    'status'     => 1,
                    'created_at' => $now,
                    'updated_at' => $now,
                    'options'    => json_encode([
                        'title'   => 'New Products',
                        'filters' => [
                            'new'   => 1,
                            'sort'  => 'asc',
                            'limit' => 10,
                        ],
                    ]),
                ], [
                    'id'         => 5,
                    'type'       => 'static_content',
                    'name'       => 'Top Collections',
                    'sort_order' => 5,
                    'status'     => 1,
                    'created_at' => $now,
                    'updated_at' => $now,
                    'options'    => json_encode([
                        'html' => '<div class="top-collection-container"><div class="top-collection-header"><h2>The game with our new additions!</h2></div><div class="top-collection-grid"><div class="top-collection-card"><img src="" data-src="storage/theme/5/1.webp" class="lazy" width="396" height="396"><h3>Our Collections</h3></div><div class="top-collection-card"><img src="" data-src="storage/theme/5/2.webp" class="lazy" width="396" height="396"><h3>Our Collections</h3></div><div class="top-collection-card"><img src="" data-src="storage/theme/5/1.webp" class="lazy" width="396" height="396"><h3>Our Collections</h3></div><div class="top-collection-card"><img src="" data-src="storage/theme/5/2.webp" class="lazy" width="396" height="396"><h3>Our Collections</h3></div><div class="top-collection-card"><img src="" data-src="storage/theme/5/1.webp" class="lazy" width="396" height="396"><h3>Our Collections</h3></div><div class="top-collection-card"><img src="" data-src="storage/theme/5/2.webp" class="lazy" width="396" height="396"><h3>Our Collections</h3></div></div></div>',

                        'css'  => '.top-collection-header {padding-left: 15px;padding-right: 15px;text-align: center;font-size: 70px;line-height: 90px;color: #060C3B;margin-top: 80px;}.top-collection-header h2 {max-width: 595px;margin-left: auto;margin-right: auto;font-family: DM Serif Display;}.top-collection-grid {display: flex;flex-wrap: wrap;gap: 32px;justify-content: center;margin-top: 60px;width: 100%;margin-right: auto;margin-left: auto;padding-right: 90px;padding-left: 90px;}.top-collection-card {position: relative;background: #f9fafb;}.top-collection-card img {border-radius: 16px;max-width: 396px;min-height: 396px;text-indent:-9999px;}.top-collection-card h3 {color: #060C3B;font-size: 30px;font-family: DM Serif Display;transform: translate(-50%, -50%);width: max-content;left: 50%;bottom: 30px;position: absolute;margin: 0;font-weight: inherit;}@media not all and (min-width: 525px) {.top-collection-header {margin-top: 30px;}.top-collection-header {font-size: 32px;line-height: 1.5;}.top-collection-grid {gap: 15px;}}@media not all and (min-width: 1024px) {.top-collection-grid {padding-left: 30px;padding-right: 30px;}}@media (max-width: 640px) {.top-collection-grid {margin-top: 20px;}}'
                    ]),
                ], [
                    'id'         => 6,
                    'type'       => 'static_content',
                    'name'       => 'Bold Collections',
                    'sort_order' => 6,
                    'status'     => 1,
                    'created_at' => $now,
                    'updated_at' => $now,
                    'options'    => json_encode([
                        'html' => '<div class="container section-gap"> <div class="inline-col-wrapper"> <div class="inline-col-image-wrapper"> <img src="" data-src="storage/theme/6/1.webp" class="lazy" width="632" height="510"> </div> <div class="inline-col-content-wrapper"> <h2 class="inline-col-title"> Get Ready for our new Bold Collections! </h2> <p class="inline-col-description">Buy products in groups for bigger discounts. like lorem ipsume is simply text for digital platform</p> <button class="primary-button">View All</button> </div> </div> </div>',
                        
                        'css'  => '.section-gap {margin-top: 80px;}.direction-ltr {direction: ltr;}.direction-rtl {direction: rtl;}.inline-col-wrapper {display: grid;grid-template-columns: auto 1fr;grid-gap: 60px;align-items: center;}.inline-col-wrapper .inline-col-image-wrapper {max-width: 632px;}.inline-col-wrapper .inline-col-image-wrapper img {max-width: 100%;height: auto;border-radius: 16px;text-indent: -9999px;}.inline-col-wrapper .inline-col-content-wrapper {display: flex;flex-wrap: wrap;gap: 20px;max-width: 464px;}.inline-col-wrapper .inline-col-content-wrapper .inline-col-title {max-width: 442px;font-size: 60px;font-weight: 400;color: #060C3B;line-height: 70px;font-family:DM Serif Display;margin: 0px;}.inline-col-wrapper .inline-col-content-wrapper .inline-col-description {margin: 0px;font-size: 18px;color: #7D7D7D;font-family:\'Poppins\';}@media (max-width:991px) {.inline-col-wrapper {grid-template-columns: 1fr;grid-gap: 16px;}.inline-col-wrapper .inline-col-content-wrapper {gap: 10px;}}@media (max-width:525px) {.inline-col-wrapper .inline-col-content-wrapper .inline-col-title {font-size: 30px;line-height: normal;}}',
                    ]),
                ], [
                    'id'         => 7,
                    'type'       => 'product_carousel',
                    'name'       => 'Featured Collections',
                    'sort_order' => 7,
                    'status'     => 1,
                    'created_at' => $now,
                    'updated_at' => $now,
                    'options'    => json_encode([
                        'title'   => 'Featured Products',
                        'filters' => [
                            'featured' => 1,
                            'sort'     => 'desc',
                            'limit'    => 10,
                        ],
                    ]),
                ], [
                    'id'         => 8,
                    'type'       => 'static_content',
                    'name'       => 'Game Container',
                    'sort_order' => 8,
                    'status'     => 1,
                    'created_at' => $now,
                    'updated_at' => $now,
                    'options'    => json_encode([
                        'html' => '<div class="section-title"> <h2>The game with our new additions!</h2> </div> <div class="container section-gap"> <div class="collection-card-wrapper"> <div class="single-collection-card"> <img src="" data-src="storage/theme/8/1.webp" class="lazy" width="615" height="600"> <h3 class="overlay-text">Our Collections</h3> </div> <div class="single-collection-card"> <img src="" data-src="storage/theme/8/2.webp" class="lazy" width="615" height="600"> <h3 class="overlay-text"> Our Collections </h3> </div> </div> </div>',

                        'css'  => '.section-title {margin-top: 80px;padding-left: 15px;padding-right: 15px;text-align: center;font-family: DM Serif Display;font-weight: 400;line-height: 90px;}.section-title h2 {font-size: 70px;color: #060C3B;font-weight: normal;max-width: 595px;margin: auto;font-family: DM Serif Display;}.collection-card-wrapper {display: flex;justify-content: center;gap: 30px;}.collection-card-wrapper .single-collection-card {position: relative;}.collection-card-wrapper .single-collection-card img {border-radius: 16px;background-color: #F5F5F5;max-width: 100%;height: auto;text-indent: -9999px;}.collection-card-wrapper .single-collection-card .overlay-text {font-size: 50px;font-weight: normal;max-width: 234px;font-style: italic;color: #060C3B;font-family: DM Serif Display;position: absolute;bottom: 30px;left: 30px;margin: 0px;}@media (max-width:1024px) {.section-title {padding: 0px 30px;}}@media (max-width:991px) {.collection-card-wrapper {flex-wrap: wrap;}}@media (max-width:525px) {.collection-card-wrapper .single-collection-card .overlay-text {font-size: 30px;}.container {padding:0px 30px;margin-top: 20px;}.section-title {margin-top: 30px;}.section-title h2 {font-size: 30px;line-height: normal;}}',
                    ]),
                ], [
                    'id'         => 9,
                    'type'       => 'product_carousel',
                    'name'       => 'All Products',
                    'sort_order' => 9,
                    'status'     => 1,
                    'created_at' => $now,
                    'updated_at' => $now,
                    'options'    => json_encode([
                        'title'   => 'All Products',
                        'filters' => [
                            'sort'  => 'desc',
                            'limit' => 10,
                        ],
                    ]),
                ], [
                    'id'         => 10,
                    'type'       => 'static_content',
                    'name'       => 'Bold Collections',
                    'sort_order' => 10,
                    'status'     => 1,
                    'created_at' => $now,
                    'updated_at' => $now,
                    'options'    => json_encode([
                        'html' => '<div class="container section-gap"> <div class="inline-col-wrapper direction-rtl"> <div class="inline-col-image-wrapper "> <img src="" data-src="storage/theme/10/1.webp" class="lazy" width="632" height="510"> </div> <div class="inline-col-content-wrapper direction-ltr"> <h2 class="inline-col-title"> Get Ready for our new Bold Collections! </h2> <p class="inline-col-description">Buy products in groups for bigger discounts. like lorem ipsume is simply text for digital platform</p> <button class="primary-button">View All</button> </div> </div> </div>',

                        'css'  => '.section-gap {margin-top: 80px;}.direction-ltr {direction: ltr;}.direction-rtl {direction: rtl;}.inline-col-wrapper {display: grid;grid-template-columns: auto 1fr;grid-gap: 60px;align-items: center;}.inline-col-wrapper .inline-col-image-wrapper {max-width: 632px;}.inline-col-wrapper .inline-col-image-wrapper img {max-width: 100%;height: auto;border-radius: 16px;text-indent: -9999px;}.inline-col-wrapper .inline-col-content-wrapper {display: flex;flex-wrap: wrap;gap: 20px;max-width: 464px;}.inline-col-wrapper .inline-col-content-wrapper .inline-col-title {max-width: 442px;font-size: 60px;font-weight: 400;color: #060C3B;line-height: 70px;font-family: DM Serif Display;margin: 0px;}.inline-col-wrapper .inline-col-content-wrapper .inline-col-description {margin: 0px;font-size: 18px;color: #7D7D7D;font-family:\'Poppins\';}@media (max-width:991px) {.inline-col-wrapper {grid-template-columns: 1fr;grid-gap: 16px;}.inline-col-wrapper .inline-col-content-wrapper {gap: 10px;}}@media (max-width:525px) {.inline-col-wrapper .inline-col-content-wrapper .inline-col-title {font-size: 30px;line-height: normal;}}',
                    ]),
                ], [
                    'id'         => 11,
                    'type'       => 'footer_links',
                    'name'       => 'Footer Links',
                    'sort_order' => 11,
                    'status'     => 1,
                    'created_at' => $now,
                    'updated_at' => $now,
                    'options'    => json_encode([
                        'column_1' => [
                            [
                                'url'        => 'http://localhost/page/about-us',
                                'title'      => 'About Us',
                                'sort_order' => 1,
                            ], [
                                'url'        => 'http://localhost/page/contact-us',
                                'title'      => 'Contact Us',
                                'sort_order' => 2,
                            ], [
                                'url'        => 'http://localhost/page/customer-service',
                                'title'      => 'Customer Service',
                                'sort_order' => 3,
                            ], [
                                'url'        => 'http://localhost/page/whats-new',
                                'title'      => 'What\'s new',
                                'sort_order' => 4,
                            ], [
                                'url'        => 'http://localhost/page/terms-of-use',
                                'title'      => 'Terms of use',
                                'sort_order' => 5,
                            ], [
                                'url'        => 'http://localhost/page/terms-conditions',
                                'title'      => 'Terms & Conditions',
                                'sort_order' => 6,
                            ]
                        ],

                        'column_2' => [
                            [
                                'url'        => 'http://localhost/page/privacy-policy',
                                'title'      => 'Privacy Policy',
                                'sort_order' => 1,
                            ], [
                                'url'        => 'http://localhost/page/payment-policy',
                                'title'      => 'Payment Policy',
                                'sort_order' => 2,
                            ], [
                                'url'        => 'http://localhost/page/shipping-policy',
                                'title'      => 'Shipping Policy',
                                'sort_order' => 3,
                            ], [
                                'url'        => 'http://localhost/page/refund-policy',
                                'title'      => 'Refund Policy',
                                'sort_order' => 4,
                            ], [
                                'url'        => 'http://localhost/page/return-policy',
                                'title'      => 'Return Policy',
                                'sort_order' => 5,
                            ], 
                        ],
                    ]),
                ],
            ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('theme_customizations');
    }
};
