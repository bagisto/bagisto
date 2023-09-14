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
                        'html' => '<div class="collection-heading"><h2>The game with our new additions!</h2></div><div class="top-collection-container"><div class="top-collection-list"><div class="collection-grid"><img data-src="storage/theme/5/1.webp" class="lazy"><h3>Our Collections</h3></div><div class="collection-grid"><img data-src="storage/theme/5/2.webp" class="lazy"><h3>Our Collections</h3></div><div class="collection-grid"><img data-src="storage/theme/5/1.webp" class="lazy"><h3>Our Collections</h3></div><div class="collection-grid"><img data-src="storage/theme/5/2.webp" class="lazy"><h3>Our Collections</h3></div><div class="collection-grid"><img data-src="storage/theme/5/1.webp" class="lazy"><h3>Our Collections</h3></div><div class="collection-grid"><img data-src="storage/theme/5/2.webp" class="lazy"><h3>Our Collections</h3></div></div></div>',
                        'css'  => '.collection-heading { padding-left: 15px; padding-right: 15px; text-align: center; font-family: DM Serif Display; font-size: 70px; font-weight: 400; line-height: 90px; --tw-text-opacity: 1; color: rgb(6 12 59 / var(--tw-text-opacity)); margin-top: 5rem; } @media not all and (min-width: 525px) { .collection-heading { margin-top: 30px; } } @media not all and (min-width: 525px){ .collection-heading { font-size: 32px; line-height: 1.5; } } .collection-heading h2 { max-width: 595px; margin-left: auto; margin-right: auto; font-family: DM Serif Display; } .top-collection-container { margin-top: 60px; width: 100%; margin-right: auto; margin-left: auto; padding-right: 90px; padding-left: 90px; } @media not all and (min-width: 1024px) { .top-collection-container { padding-left: 30px; padding-right: 30px; } } @media (max-width: 640px) { .top-collection-container { margin-top: 20px; } } .top-collection-container .top-collection-list { gap: 2rem; justify-content: center; flex-wrap: wrap; display: flex; } @media not all and (min-width: 525px) { .top-collection-list { gap: 15px; } } .collection-grid { position: relative; } .collection-grid img { --tw-bg-opacity: 1; background-color: rgb(245 245 245 / var(--tw-bg-opacity)); border-radius: 1rem; max-width: 396px; min-height: 396px; max-width: 100%; height: auto; display: block; vertical-align: middle; } @media not all and (min-width: 525px) { .collection-grid img { max-width: 100%; } } @media not all and (min-width: 525px) { .collection-grid img { min-height: 100%; } } .collection-grid h3 { --tw-text-opacity: 1; color: rgb(6 12 59 / var(--tw-text-opacity)); font-size: 30px; font-family: DM Serif Display; --tw-translate-x: -50%; transform: translate(var(--tw-translate-x), var(--tw-translate-y)) rotate(var(--tw-rotate)) skewX(var(--tw-skew-x)) skewY(var(--tw-skew-y)) scaleX(var(--tw-scale-x)) scaleY(var(--tw-scale-y)); width: -moz-max-content; width: max-content; left: 50%; bottom: 30px; position: absolute; margin: 0; font-weight: inherit; }'
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
                        'html' => '<div class="bold-collection"><div class="bold-collection-grid"><div class="bold-collection-image-container"><img class="rounded-2xl lazy" data-src="storage/theme/6/1.webp"></div><div class="bold-collection-title"><h2>Get Ready for our new Bold Collections!</h2><p>Buy products in groups for bigger discounts. like lorem ipsume is simply text for digital platform</p><button>View All</button></div></div></div>',
                        'css'  => '.bold-collection { margin-top: 5rem; width: 100%; margin-right: auto; margin-left: auto; padding-right: 90px; padding-left: 90px; } @media not all and (min-width: 525px) { .bold-collection { margin-top: 30px; } } @media not all and (min-width: 1024px) { .bold-collection { padding-left: 30px; padding-right: 30px; } } @media (max-width: 1023px) { .bold-collection { padding-left: 30px; padding-right: 30px; } } @media (min-width: 1440px) { .bold-collection { max-width: 1440px; } } .bold-collection .bold-collection-grid { -moz-column-gap: 60px; column-gap: 60px; align-items: center; grid-template-columns: auto 1fr; display: grid; } @media not all and (min-width: 991px) { .bold-collection .bold-collection-grid { gap: 16px; } } @media not all and (min-width: 991px) { .bold-collection .bold-collection-grid { grid-template-columns: 1fr; } } .bold-collection .bold-collection-grid .bold-collection-image-container { max-width: 632px; } .bold-collection .bold-collection-grid .bold-collection-image-container img { border-radius: 1rem; max-width: 100%; height: auto; display: block; vertical-align: middle; } .bold-collection .bold-collection-grid .bold-collection-title { gap: 20px; flex-wrap: wrap; max-width: 464px; display: flex; } .bold-collection .bold-collection-grid .bold-collection-title h2 { --tw-text-opacity: 1; color: rgb(6 12 59 / var(--tw-text-opacity)); line-height: 70px; font-weight: 400 !important; font-size: 60px !important; font-family: DM Serif Display; max-width: 442px; margin: 0; font-size: inherit; font-weight: inherit; } @media not all and (min-width: 525px) { .bold-collection .bold-collection-grid .bold-collection-title h2 { line-height: 1.5; } } @media not all and (min-width: 525px) { .bold-collection .bold-collection-grid .bold-collection-title h2 { font-size: 30px !important; } } .bold-collection .bold-collection-grid .bold-collection-title p { --tw-text-opacity: 1; color: rgb(125 125 125 / var(--tw-text-opacity)); font-size: 18px; margin: 0; } .bold-collection .bold-collection-grid .bold-collection-title button { --tw-text-opacity: 1; color: rgb(255 255 255 / var(--tw-text-opacity)); font-weight: 500; font-size: 1rem; line-height: 1.5rem; text-align: center; padding-top: 11px; padding-bottom: 11px; padding-left: 43px; padding-right: 43px; --tw-bg-opacity: 1; background-color: rgb(6 12 59 / var(--tw-bg-opacity)); --tw-border-opacity: 1; border-color: rgb(6 12 59 / var(--tw-border-opacity)); border-radius: 18px; width: -moz-max-content; width: max-content; display: block; cursor: pointer; -webkit-appearance: button; background-image: none; text-transform: none; margin: 0; }',
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
                        'html' => '<div class="game-edition"><h2>The game with our new additions!</h2></div><div class="game-container"><div class="game-container-box"><div class="game-container-item"><img data-src="storage/theme/8/1.webp" class="lazy"><h3>Our Collections</h3></div><div class="game-container-item"><img data-src="storage/theme/8/2.webp" class="lazy"><h3>Our Collections</h3></div></div></div>',
                        'css'  => '.game-edition { margin-top: 5rem; padding-left: 15px; padding-right: 15px; text-align: center; font-family: DM Serif Display; font-size: 70px; font-weight: 400; line-height: 90px; --tw-text-opacity: 1; color: rgb(6 12 59 / var(--tw-text-opacity)); } @media not all and (min-width: 1024px) { .game-edition { padding-left: 30px; padding-right: 30px; } } @media not all and (min-width: 525px) { .game-edition { margin-top: 30px; } } @media not all and (min-width: 525px) { .game-edition { font-size: 32px; line-height: 1.5; } } .game-edition h2 { font-family: DM Serif Display; max-width: 595px; margin-left: auto !important; margin-right: auto !important; margin: 0; font-size: inherit; font-weight: inherit; } .game-container { margin-top: 60px; width: 100%; margin-right: auto; margin-left: auto; padding-right: 90px; padding-left: 90px; } @media not all and (min-width: 525px) { .game-container { margin-top: 20px; } } @media not all and (min-width: 525px) { .game-container { margin-top: 20px; } } @media not all and (min-width: 1024px) { .game-container { padding-left: 30px; padding-right: 30px; } } @media not all and (min-width: 1024px) { .game-containtw-bg-opacity: 1; background-color: rgb(245 245 245 / var(--tw-bg-opacity)); border-radius: 1rem; max-width: 100%; height: auto; display: block; vertical-align: middleer { padding-left: 30px; padding-right: 30px; } } .game-container .game-container-box { gap: 30px; justify-content: center; display: flex; } @media not all and (min-width: 991px) { .game-container .game-container-box { flex-wrap: wrap; } } .game-container-item { position: relative; } .game-container-item img { --tw-bg-opacity: 1; background-color: rgb(245 245 245 / var(--tw-bg-opacity)); border-radius: 1rem; max-width: 100%; height: auto; display: block; vertical-align: middle; --tw-bg-opacity: 1; } @media not all and (min-width: 525px) { .game-container-item img { max-width: 100%; } } @media not all and (min-width: 525px) { .game-container-item img { min-height: 100%; } } .game-container-item h3 { --tw-text-opacity: 1; color: rgb(6 12 59 / var(--tw-text-opacity)); font-style: italic; font-size: 50px !important; font-family: DM Serif Display; max-width: 234px; left: 30px; bottom: 30px; position: absolute; margin: 0; font-size: inherit; font-weight: inherit; } @media not all and (min-width: 525px) { .game-container-item { font-size: 30px; } }',
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
                        'html' => '<div class="bold-collection"><div class="bold-collection-grid"><div class="bold-collection-title"><h2>Get Ready for our new Bold Collections!</h2><p>Buy products in groups for bigger discounts. like lorem ipsume is simply text for digital platform</p><button>View All</button></div><div class="bold-collection-image-container"><img class="rounded-2xl lazy" data-src="storage/theme/10/1.webp"></div></div></div>',
                        'css'  => '.bold-collection { margin-top: 5rem; width: 100%; margin-right: auto; margin-left: auto; padding-right: 90px; padding-left: 90px; } @media not all and (min-width: 525px) { .bold-collection { margin-top: 30px; } } @media not all and (min-width: 1024px) { .bold-collection { padding-left: 30px; padding-right: 30px; } } @media (max-width: 1023px) { .bold-collection { padding-left: 30px; padding-right: 30px; } } @media (min-width: 1440px) { .bold-collection { max-width: 1440px; } } .bold-collection .bold-collection-grid { -moz-column-gap: 60px; column-gap: 60px; align-items: center; grid-template-columns: auto 1fr; display: grid; } @media not all and (min-width: 991px) { .bold-collection .bold-collection-grid { gap: 16px; } } @media not all and (min-width: 991px) { .bold-collection .bold-collection-grid { grid-template-columns: 1fr; } } .bold-collection .bold-collection-grid .bold-collection-image-container { max-width: 632px; } .bold-collection .bold-collection-grid .bold-collection-image-container img { border-radius: 1rem; max-width: 100%; height: auto; display: block; vertical-align: middle; } .bold-collection .bold-collection-grid .bold-collection-title { gap: 20px; flex-wrap: wrap; max-width: 464px; display: flex; } .bold-collection .bold-collection-grid .bold-collection-title h2 { --tw-text-opacity: 1; color: rgb(6 12 59 / var(--tw-text-opacity)); line-height: 70px; font-weight: 400 !important; font-size: 60px !important; font-family: DM Serif Display; max-width: 442px; margin: 0; font-size: inherit; font-weight: inherit; } @media not all and (min-width: 525px) { .bold-collection .bold-collection-grid .bold-collection-title h2 { line-height: 1.5; } } @media not all and (min-width: 525px) { .bold-collection .bold-collection-grid .bold-collection-title h2 { font-size: 30px !important; } } .bold-collection .bold-collection-grid .bold-collection-title p { --tw-text-opacity: 1; color: rgb(125 125 125 / var(--tw-text-opacity)); font-size: 18px; margin: 0; } .bold-collection .bold-collection-grid .bold-collection-title button { --tw-text-opacity: 1; color: rgb(255 255 255 / var(--tw-text-opacity)); font-weight: 500; font-size: 1rem; line-height: 1.5rem; text-align: center; padding-top: 11px; padding-bottom: 11px; padding-left: 43px; padding-right: 43px; --tw-bg-opacity: 1; background-color: rgb(6 12 59 / var(--tw-bg-opacity)); --tw-border-opacity: 1; border-color: rgb(6 12 59 / var(--tw-border-opacity)); border-radius: 18px; width: -moz-max-content; width: max-content; display: block; cursor: pointer; -webkit-appearance: button; background-image: none; text-transform: none; margin: 0; }',
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
