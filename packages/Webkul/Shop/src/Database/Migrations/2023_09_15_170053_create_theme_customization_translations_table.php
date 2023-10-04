<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('theme_customization_translations', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('theme_customization_id')->unsigned();
            $table->string('locale');
            $table->json('options');
            $table->foreign('theme_customization_id')->references('id')->on('theme_customizations')->onDelete('cascade');
        });

        DB::table('theme_customization_translations')   
        ->insert([
            [
                'theme_customization_id' => 1,
                'locale'                 => 'en',
                'options'                => json_encode([
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
                'theme_customization_id' => 2,
                'locale'                 => 'en',
                'options'                => json_encode([
                    'html' => '<div class="home-offer"><span>Get UPTO 40% OFF on your 1st order SHOP NOW</span></div>',
                    'css'  => '.home-offer span {display: block;font-weight: 500;text-align: center;font-size: 22px;font-family: DM Serif Display;background-color: #E8EDFE;padding-top: 20px;padding-bottom: 20px;}@media (max-width:768px){.home-offer span {font-size:18px;}@media (max-width:525px) {.home-offer span {font-size:14px;}}'
                ]),
            ], [
                'theme_customization_id' => 3,
                'locale'                 => 'en',
                'options'                => json_encode([
                    'filters' => [
                        'parent_id'  => 1,
                        'sort'      => 'asc',
                        'limit'     => 10,
                    ],
                ]),
            ], [
                'theme_customization_id' => 4,
                'locale'                 => 'en',
                'options'                => json_encode([
                    'title'   => 'New Products',
                    'filters' => [
                        'new'   => 1,
                        'sort'  => 'asc',
                        'limit' => 10,
                    ],
                ]),
            ], [
                'theme_customization_id' => 5,
                'locale'                 => 'en',
                'options'                => json_encode([
                    'html' => '<div class="top-collection-container"><div class="top-collection-header"><h2>The game with our new additions!</h2></div><div class="container top-collection-grid"><div class="top-collection-card"><img src="" data-src="storage/theme/5/1.webp" class="lazy" width="396" height="396" alt=""><h3>Our Collections</h3></div><div class="top-collection-card"><img src="" data-src="storage/theme/5/2.webp" class="lazy" width="396" height="396" alt=""><h3>Our Collections</h3></div><div class="top-collection-card"><img src="" data-src="storage/theme/5/1.webp" class="lazy" width="396" height="396" alt=""><h3>Our Collections</h3></div><div class="top-collection-card"><img src="" data-src="storage/theme/5/2.webp" class="lazy" width="396" height="396" alt=""><h3>Our Collections</h3></div><div class="top-collection-card"><img src="" data-src="storage/theme/5/1.webp" class="lazy" width="396" height="396" alt=""><h3>Our Collections</h3></div><div class="top-collection-card"><img src="" data-src="storage/theme/5/2.webp" class="lazy" width="396" height="396" alt=""><h3>Our Collections</h3></div></div></div>',

                    'css'  => '.top-collection-header {padding-left: 15px;padding-right: 15px;text-align: center;font-size: 70px;line-height: 90px;color: #060C3B;margin-top: 80px;}.top-collection-header h2 {max-width: 595px;margin-left: auto;margin-right: auto;font-family: DM Serif Display;}.top-collection-grid {display: flex;flex-wrap: wrap;gap: 32px;justify-content: center;margin-top: 60px;width: 100%;margin-right: auto;margin-left: auto;padding-right: 90px;padding-left: 90px;}.top-collection-card {position: relative;background: #f9fafb;}.top-collection-card img {border-radius: 16px;max-width: 100%;text-indent:-9999px;}.top-collection-card h3 {color: #060C3B;font-size: 30px;font-family: DM Serif Display;transform: translateX(-50%);width: max-content;left: 50%;bottom: 30px;position: absolute;margin: 0;font-weight: inherit;}@media not all and (min-width: 525px) {.top-collection-header {margin-top: 30px;}.top-collection-header {font-size: 32px;line-height: 1.5;}.top-collection-grid {gap: 15px;}}@media not all and (min-width: 1024px) {.top-collection-grid {padding-left: 30px;padding-right: 30px;}}@media (max-width: 640px) {.top-collection-grid {margin-top: 20px;}}'
                ]),
            ], [
                'theme_customization_id' => 6,
                'locale'                 => 'en',
                'options'                => json_encode([
                    'html' => '<div class="container section-gap"> <div class="inline-col-wrapper"> <div class="inline-col-image-wrapper"> <img src="" data-src="storage/theme/6/1.webp" class="lazy" width="632" height="510" alt=""> </div> <div class="inline-col-content-wrapper"> <h2 class="inline-col-title"> Get Ready for our new Bold Collections! </h2> <p class="inline-col-description">Buy products in groups for bigger discounts. like lorem ipsume is simply text for digital platform</p> <button class="primary-button">View All</button> </div> </div> </div>',
                    
                    'css'  => '.section-gap{margin-top:80px}.direction-ltr{direction:ltr}.direction-rtl{direction:rtl}.inline-col-wrapper{display:grid;grid-template-columns:auto 1fr;grid-gap:60px;align-items:center}.inline-col-wrapper .inline-col-image-wrapper{overflow:hidden}.inline-col-wrapper .inline-col-image-wrapper img{max-width:100%;height:auto;border-radius:16px;text-indent:-9999px}.inline-col-wrapper .inline-col-content-wrapper{display:flex;flex-wrap:wrap;gap:20px;max-width:464px}.inline-col-wrapper .inline-col-content-wrapper .inline-col-title{max-width:442px;font-size:60px;font-weight:400;color:#060c3b;line-height:70px;font-family:DM Serif Display;margin:0}.inline-col-wrapper .inline-col-content-wrapper .inline-col-description{margin:0;font-size:18px;color:#6e6e6e;font-family:Poppins}@media (max-width:991px){.inline-col-wrapper{grid-template-columns:1fr;grid-gap:16px}.inline-col-wrapper .inline-col-content-wrapper{gap:10px}}@media (max-width:525px){.inline-col-wrapper .inline-col-content-wrapper .inline-col-title{font-size:30px;line-height:normal}}',
                ]),
            ], [
                'theme_customization_id' => 7,
                'locale'                 => 'en',
                'options'                => json_encode([
                    'title'   => 'Featured Products',
                    'filters' => [
                        'featured' => 1,
                        'sort'     => 'desc',
                        'limit'    => 10,
                    ],
                ]),
            ], [
                'theme_customization_id' => 8,
                'locale'                 => 'en',
                'options'                => json_encode([
                    'html' => '<div class="section-title"> <h2>The game with our new additions!</h2> </div> <div class="container section-gap"> <div class="collection-card-wrapper"> <div class="single-collection-card"> <img src="" data-src="storage/theme/8/1.webp" class="lazy" width="615" height="600" alt=""> <h3 class="overlay-text">Our Collections</h3> </div> <div class="single-collection-card"> <img src="" data-src="storage/theme/8/2.webp" class="lazy" width="615" height="600" alt=""> <h3 class="overlay-text"> Our Collections </h3> </div> </div> </div>',

                    'css'  => '.section-title,.section-title h2{font-weight:400;font-family:DM Serif Display}.section-title{margin-top:80px;padding-left:15px;padding-right:15px;text-align:center;line-height:90px}.section-title h2{font-size:70px;color:#060c3b;max-width:595px;margin:auto}.collection-card-wrapper{display:flex;flex-wrap:wrap;justify-content:center;gap:30px}.collection-card-wrapper .single-collection-card{position:relative}.collection-card-wrapper .single-collection-card img{border-radius:16px;background-color:#f5f5f5;max-width:100%;height:auto;text-indent:-9999px}.collection-card-wrapper .single-collection-card .overlay-text{font-size:50px;font-weight:400;max-width:234px;font-style:italic;color:#060c3b;font-family:DM Serif Display;position:absolute;bottom:30px;left:30px;margin:0}@media (max-width:1024px){.section-title{padding:0 30px}}@media (max-width:991px){.collection-card-wrapper{flex-wrap:wrap}}@media (max-width:525px){.collection-card-wrapper .single-collection-card img{max-width:calc(100vw - 30px)}.collection-card-wrapper .single-collection-card .overlay-text{font-size:30px}.container{padding:0 30px;margin-top:20px}.section-title{margin-top:30px}.section-title h2{font-size:30px;line-height:normal}}',
                ]),
            ], [
                'theme_customization_id' => 9,
                'locale'                 => 'en',
                'options'                => json_encode([
                    'title'   => 'All Products',
                    'filters' => [
                        'sort'  => 'desc',
                        'limit' => 10,
                    ],
                ]),
            ], [
                'theme_customization_id' => 10,
                'locale'                 => 'en',
                'options'                => json_encode([
                    'html' => '<div class="container section-gap"> <div class="inline-col-wrapper direction-rtl"> <div class="inline-col-image-wrapper "> <img src="" data-src="storage/theme/10/1.webp" class="lazy" width="632" height="510" alt=""> </div> <div class="inline-col-content-wrapper direction-ltr"> <h2 class="inline-col-title"> Get Ready for our new Bold Collections! </h2> <p class="inline-col-description">Buy products in groups for bigger discounts. like lorem ipsume is simply text for digital platform</p> <button class="primary-button">View All</button> </div> </div> </div>',

                    'css'  => '.section-gap{margin-top:80px}.direction-ltr{direction:ltr}.direction-rtl{direction:rtl}.inline-col-wrapper{display:grid;grid-template-columns:auto 1fr;grid-gap:60px;align-items:center}.inline-col-wrapper .inline-col-image-wrapper{overflow:hidden}.inline-col-wrapper .inline-col-image-wrapper img{max-width:100%;height:auto;border-radius:16px;text-indent:-9999px}.inline-col-wrapper .inline-col-content-wrapper{display:flex;flex-wrap:wrap;gap:20px;max-width:464px}.inline-col-wrapper .inline-col-content-wrapper .inline-col-title{max-width:442px;font-size:60px;font-weight:400;color:#060c3b;line-height:70px;font-family:DM Serif Display;margin:0}.inline-col-wrapper .inline-col-content-wrapper .inline-col-description{margin:0;font-size:18px;color:#6e6e6e;font-family:Poppins}@media (max-width:991px){.inline-col-wrapper{grid-template-columns:1fr;grid-gap:16px}.inline-col-wrapper .inline-col-content-wrapper{gap:10px}}@media (max-width:525px){.inline-col-wrapper .inline-col-content-wrapper .inline-col-title{font-size:30px;line-height:normal}}',
                ]),
            ], [
                'theme_customization_id' => 11,
                'locale'                => 'en',
                'options'               => json_encode([
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
        Schema::dropIfExists('theme_customization_translations');
    }
};
