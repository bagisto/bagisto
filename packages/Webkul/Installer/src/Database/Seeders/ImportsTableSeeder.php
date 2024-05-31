<?php

namespace Webkul\Installer\Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Http\File;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ImportsTableSeeder extends Seeder
{
    /**
     * Base path for the images.
     */
    const BASE_PATH = 'packages/Webkul/Installer/src/Resources/assets/images/seeders/sampleProducts/';

    /**
     * Seed the application's database.
     *
     * @param  array  $parameters
     * @return void
     */
    public function run()
    {
        DB::table('products')->delete();

        DB::table('product_flat')->delete();

        $now = Carbon::now();

        DB::table('products')->insert([
            [
                'id'                  => 1,
                'sku'                 => 'SP-001',
                'type'                => 'simple',
                'parent_id'           => null,
                'attribute_family_id' => 1,
                'additional'          => null,
                'created_at'          => $now,
                'updated_at'          => $now,
            ], [
                'id'                  => 2,
                'sku'                 => 'SP-002',
                'type'                => 'simple',
                'parent_id'           => null,
                'attribute_family_id' => 1,
                'additional'          => null,
                'created_at'          => $now,
                'updated_at'          => $now,
            ], [
                'id'                  => 3,
                'sku'                 => 'SP-003',
                'type'                => 'simple',
                'parent_id'           => null,
                'attribute_family_id' => 1,
                'additional'          => null,
                'created_at'          => $now,
                'updated_at'          => $now,
            ], [
                'id'                  => 4,
                'sku'                 => 'SP-004',
                'type'                => 'simple',
                'parent_id'           => null,
                'attribute_family_id' => 1,
                'additional'          => null,
                'created_at'          => $now,
                'updated_at'          => $now,
            ],
        ]);

        DB::table('product_flat')->insert([
            [
                'id'                   => 1,
                'sku'                  => 'SP-001',
                'type'                 => 'simple',
                'product_number'       => '',
                'name'                 => 'Arctic Cozy Knit Unisex Beanie',
                'short_description'    => 'Embrace the chilly days in style with our Arctic Cozy Knit Beanie. Crafted from a soft and durable blend of acrylic, this classic beanie offers warmth and versatility. Suitable for both men and women, it\'s the ideal accessory for casual or outdoor wear. Elevate your winter wardrobe or gift someone special with this essential beanie cap.',
                'description'          => 'The Arctic Cozy Knit Beanie is your go-to solution for staying warm, comfortable, and stylish during the colder months. Crafted from a soft and durable blend of acrylic knit, this beanie is designed to provide a cozy and snug fit. The classic design makes it suitable for both men and women, offering a versatile accessory that complements various styles. Whether you\'re heading out for a casual day in town or embracing the great outdoors, this beanie adds a touch of comfort and warmth to your ensemble. The soft and breathable material ensures that you stay cozy without sacrificing style. The Arctic Cozy Knit Beanie isn\'t just an accessory; it\'s a statement of winter fashion. Its simplicity makes it easy to pair with different outfits, making it a staple in your winter wardrobe. Ideal for gifting or as a treat for yourself, this beanie is a thoughtful addition to any winter ensemble. It\'s a versatile accessory that goes beyond functionality, adding a touch of warmth and style to your look. Embrace the essence of winter with the Arctic Cozy Knit Beanie. Whether you\'re enjoying a casual day out or facing the elements, let this beanie be your companion for comfort and style. Elevate your winter wardrobe with this classic accessory that effortlessly combines warmth with a timeless sense of fashion.',
                'url_key'              => 'arctic-cozy-knit-unisex-beanie',
                'new'                  => 1,
                'featured'             => 1,
                'status'               => 1,
                'meta_title'           => 'Meta Title',
                'meta_keywords'        => 'meta1, meta2, meta3',
                'meta_description'     => 'meta description',
                'price'                => 14,
                'special_price'        => null,
                'special_price_from'   => null,
                'special_price_to'     => null,
                'weight'               => 1.23,
                'created_at'           => $now,
                'locale'               => 'en',
                'channel'              => 'default',
                'attribute_family_id'  => 1,
                'product_id'           => 1,
                'updated_at'           => $now,
                'parent_id'            => null,
                'visible_individually' => 1,
            ], [
                'id'                   => 2,
                'sku'                  => 'SP-002',
                'type'                 => 'simple',
                'product_number'       => '',
                'name'                 => 'Arctic Bliss Stylish Winter Scarf',
                'short_description'    => 'Experience the embrace of warmth and style with our Arctic Bliss Winter Scarf. Crafted from a luxurious blend of acrylic and wool, this cozy scarf is designed to keep you snug during the coldest days. Its stylish and versatile design, combined with an extra-long length, offers customizable styling options. Elevate your winter wardrobe or delight someone special with this essential winter accessory.',
                'description'          => 'The Arctic Bliss Winter Scarf is more than just a cold-weather accessory; it\'s a statement of warmth, comfort, and style for the winter season. Crafted with care from a luxurious blend of acrylic and wool, this scarf is designed to keep you cozy and snug even in the chilliest temperatures. The soft and plush texture not only provides insulation against the cold but also adds a touch of luxury to your winter wardrobe. The design of the Arctic Bliss Winter Scarf is both stylish and versatile, making it a perfect addition to a variety of winter outfits. Whether you\'re dressing up for a special occasion or adding a chic layer to your everyday look, this scarf complements your style effortlessly. The extra-long length of the scarf offers customizable styling options. Wrap it around for added warmth, drape it loosely for a casual look, or experiment with different knots to express your unique style. This versatility makes it a must-have accessory for the winter season. Looking for the perfect gift? The Arctic Bliss Winter Scarf is an ideal choice. Whether you\'re surprising a loved one or treating yourself, this scarf is a timeless and practical gift that will be cherished throughout the winter months. Embrace the winter with the Arctic Bliss Winter Scarf, where warmth meets style in perfect harmony. Elevate your winter wardrobe with this essential accessory that not only keeps you warm but also adds a touch of sophistication to your cold-weather ensemble.',
                'url_key'              => 'arctic-bliss-stylish-winter-scarf',
                'new'                  => 1,
                'featured'             => 1,
                'status'               => 1,
                'meta_title'           => 'Meta Title',
                'meta_keywords'        => 'meta1, meta2, meta3',
                'meta_description'     => 'meta description',
                'price'                => 17,
                'special_price'        => null,
                'special_price_from'   => null,
                'special_price_to'     => null,
                'weight'               => 1.23,
                'created_at'           => $now,
                'locale'               => 'en',
                'channel'              => 'default',
                'attribute_family_id'  => 1,
                'product_id'           => 2,
                'updated_at'           => $now,
                'parent_id'            => null,
                'visible_individually' => 1,
            ], [
                'id'                   => 3,
                'sku'                  => 'SP-003',
                'type'                 => 'simple',
                'product_number'       => '',
                'name'                 => 'Arctic Touchscreen Winter Gloves',
                'short_description'    => 'Stay connected and warm with our Arctic Touchscreen Winter Gloves. These gloves are not only crafted from high-quality acrylic for warmth and durability but also feature a touchscreen-compatible design. With an insulated lining, elastic cuffs for a secure fit, and a stylish look, these gloves are perfect for daily wear in chilly conditions.',
                'description'          => 'Introducing the Arctic Touchscreen Winter Gloves – where warmth, style, and connectivity meet to enhance your winter experience. Crafted from high-quality acrylic, these gloves are designed to provide exceptional warmth and durability. The touchscreen-compatible fingertips allow you to stay connected without exposing your hands to the cold. Answer calls, send messages, and navigate your devices effortlessly, all while keeping your hands snug. The insulated lining adds an extra layer of coziness, making these gloves your go-to choice for facing the winter chill. Whether you\'re commuting, running errands, or enjoying outdoor activities, these gloves provide the warmth and protection you need. Elastic cuffs ensure a secure fit, preventing cold drafts and keeping the gloves in place during your daily activities. The stylish design adds a touch of flair to your winter ensemble, making these gloves as fashionable as they are functional. Ideal for gifting or as a treat for yourself, the Arctic Touchscreen Winter Gloves are a must-have accessory for the modern individual. Say goodbye to the inconvenience of removing your gloves to use your devices and embrace the seamless blend of warmth, style, and connectivity. Stay connected, stay warm, and stay stylish with the Arctic Touchscreen Winter Gloves – your reliable companion for conquering the winter season with confidence.',
                'url_key'              => 'arctic-touchscreen-winter-gloves',
                'new'                  => 1,
                'featured'             => 1,
                'status'               => 1,
                'meta_title'           => 'Meta Title',
                'meta_keywords'        => 'meta1, meta2, meta3',
                'meta_description'     => 'meta description',
                'price'                => 21,
                'special_price'        => 17,
                'special_price_from'   => $now,
                'special_price_to'     => Carbon::tomorrow(),
                'weight'               => 1,
                'created_at'           => $now,
                'locale'               => 'en',
                'channel'              => 'default',
                'attribute_family_id'  => 1,
                'product_id'           => 3,
                'updated_at'           => $now,
                'parent_id'            => null,
                'visible_individually' => 1,
            ], [
                'id'                   => 4,
                'sku'                  => 'SP-004',
                'type'                 => 'simple',
                'product_number'       => '',
                'name'                 => 'Arctic Warmth Wool Blend Socks',
                'short_description'    => 'Experience the unmatched warmth and comfort of our Arctic Warmth Wool Blend Socks. Crafted from a blend of Merino wool, acrylic, nylon, and spandex, these socks offer ultimate coziness for cold weather. With a reinforced heel and toe for durability, these versatile and stylish socks are perfect for various occasions.',
                'description'          => 'Introducing the Arctic Warmth Wool Blend Socks – your essential companion for cozy and comfortable feet during the colder seasons. Crafted from a premium blend of Merino wool, acrylic, nylon, and spandex, these socks are designed to provide unparalleled warmth and comfort. The wool blend ensures that your feet stay toasty even in the coldest temperatures, making these socks the perfect choice for winter adventures or simply staying snug at home. The soft and cozy texture of the socks offers a luxurious feel against your skin. Say goodbye to chilly feet as you embrace the plush warmth provided by these wool blend socks. Designed for durability, the socks feature a reinforced heel and toe, adding extra strength to high-wear areas. This ensures that your socks withstand the test of time, providing long-lasting comfort and coziness. The breathable nature of the material prevents overheating, allowing your feet to stay comfortable and dry throughout the day. Whether you\'re heading outdoors for a winter hike or relaxing indoors, these socks offer the perfect balance of warmth and breathability. Versatile and stylish, these wool blend socks are suitable for various occasions. Pair them with your favorite boots for a fashionable winter look or wear them around the house for ultimate comfort. Elevate your winter wardrobe and prioritize comfort with the Arctic Warmth Wool Blend Socks. Treat your feet to the luxury they deserve and step into a world of coziness that lasts all season long.',
                'url_key'              => 'arctic-warmth-wool-blend-socks',
                'new'                  => 0,
                'featured'             => 0,
                'status'               => 1,
                'meta_title'           => 'Meta Title',
                'meta_keywords'        => 'meta1, meta2, meta3',
                'meta_description'     => 'meta description',
                'price'                => 21,
                'special_price'        => null,
                'special_price_from'   => null,
                'special_price_to'     => null,
                'weight'               => 1,
                'created_at'           => $now,
                'locale'               => 'en',
                'channel'              => 'default',
                'attribute_family_id'  => 1,
                'product_id'           => 4,
                'updated_at'           => $now,
                'parent_id'            => null,
                'visible_individually' => 0,
            ],
        ]);

        DB::table('product_attribute_values')->insert([
            [
                'id'                   => 1,
                'locale'               => 'en',
                'channel'              => 'default',
                'text_value'           => 'Arctic Cozy Knit Unisex Beanie',
                'boolean_value'        => null,
                'integer_value'        => null,
                'float_value'          => null,
                'datetime_value'       => null,
                'date_value'           => null,
                'json_value'           => null,
                'product_id'           => 1,
                'attribute_id'         => 1,
            ], [
                'id'                   => 2,
                'locale'               => 'en',
                'channel'              => 'default',
                'text_value'           => 'Arctic Bliss Stylish Winter Scarf',
                'boolean_value'        => null,
                'integer_value'        => null,
                'float_value'          => null,
                'datetime_value'       => null,
                'date_value'           => null,
                'json_value'           => null,
                'product_id'           => 2,
                'attribute_id'         => 1,
            ], [
                'id'                   => 3,
                'locale'               => 'en',
                'channel'              => 'default',
                'text_value'           => 'Arctic Touchscreen Winter Gloves',
                'boolean_value'        => null,
                'integer_value'        => null,
                'float_value'          => null,
                'datetime_value'       => null,
                'date_value'           => null,
                'json_value'           => null,
                'product_id'           => 3,
                'attribute_id'         => 1,
            ], [
                'id'                   => 4,
                'locale'               => 'en',
                'channel'              => 'default',
                'text_value'           => 'Arctic Warmth Wool Blend Socks',
                'boolean_value'        => null,
                'integer_value'        => null,
                'float_value'          => null,
                'datetime_value'       => null,
                'date_value'           => null,
                'json_value'           => null,
                'product_id'           => 4,
                'attribute_id'         => 1,
            ],
        ]);

        DB::table('product_price_indices')->insert([
            [
                'id'                   => 1,
                'product_id'           => 1,
                'customer_group_id'    => 1,
                'channel_id'           => 1,
                'min_price'            => 14,
                'regular_min_price'    => 14,
                'max_price'            => 14,
                'regular_max_price'    => 14,
                'created_at'           => $now,
                'updated_at'           => $now,
            ], [
                'id'                   => 2,
                'product_id'           => 2,
                'customer_group_id'    => null,
                'channel_id'           => 1,
                'min_price'            => 17,
                'regular_min_price'    => 17,
                'max_price'            => 17,
                'regular_max_price'    => 17,
                'created_at'           => $now,
                'updated_at'           => $now,
            ], [
                'id'                   => 3,
                'product_id'           => 3,
                'customer_group_id'    => null,
                'channel_id'           => 1,
                'min_price'            => 21,
                'regular_min_price'    => 21,
                'max_price'            => 21,
                'regular_max_price'    => 21,
                'created_at'           => $now,
                'updated_at'           => $now,
            ], [
                'id'                   => 4,
                'product_id'           => 4,
                'customer_group_id'    => null,
                'channel_id'           => 1,
                'min_price'            => 21,
                'regular_min_price'    => 21,
                'max_price'            => 21,
                'regular_max_price'    => 21,
                'created_at'           => $now,
                'updated_at'           => $now,
            ],
        ]);

        // Product Categories
        DB::table('product_inventories')->insert([
            [
                'id'                   => 1,
                'product_id'           => 1,
                'vendor_id'            => 0,
                'inventory_source_id'  => 1,
                'qty'                  => 100,
            ], [
                'id'                   => 2,
                'product_id'           => 2,
                'vendor_id'            => 0,
                'inventory_source_id'  => 1,
                'qty'                  => 100,
            ], [
                'id'                   => 3,
                'product_id'           => 3,
                'vendor_id'            => 0,
                'inventory_source_id'  => 1,
                'qty'                  => 100,
            ], [
                'id'                   => 4,
                'product_id'           => 4,
                'vendor_id'            => 0,
                'inventory_source_id'  => 1,
                'qty'                  => 100,
            ],
        ]);

        DB::table('product_inventory_indices')->insert([
            [
                'id'                   => 1,
                'qty'                  => 100,
                'product_id'           => 1,
                'channel_id'           => 1,
                'created_at'           => $now,
                'updated_at'           => $now,
            ], [
                'id'                   => 2,
                'qty'                  => 100,
                'product_id'           => 2,
                'channel_id'           => 1,
                'created_at'           => $now,
                'updated_at'           => $now,
            ], [
                'id'                   => 3,
                'qty'                  => 100,
                'product_id'           => 3,
                'channel_id'           => 1,
                'created_at'           => $now,
                'updated_at'           => $now,
            ], [
                'id'                   => 4,
                'qty'                  => 100,
                'product_id'           => 4,
                'channel_id'           => 1,
                'created_at'           => $now,
                'updated_at'           => $now,
            ],
        ]);

        // Product Categories
        DB::table('product_categories')->insert([
            [
                'product_id'           => 1,
                'category_id'          => 1,
            ], [
                'product_id'           => 2,
                'category_id'          => 1,
            ], [
                'product_id'           => 3,
                'category_id'          => 1,
            ], [
                'product_id'           => 4,
                'category_id'          => 1,
            ],
        ]);

        DB::table('product_images')->insert([
            [
                'id'         => 1,
                'type'       => 'image',
                'path'       => $this->productImages('product/1', 'product/1/1.webp'),
                'product_id' => 1,
                'position'   => 1,
            ], [
                'id'         => 2,
                'type'       => 'image',
                'path'       => $this->productImages('product/2', 'product/2/2.webp'),
                'product_id' => 2,
                'position'   => 1,
            ], [
                'id'         => 3,
                'type'       => 'image',
                'path'       => $this->productImages('product/3', 'product/3/3.webp'),
                'product_id' => 3,
                'position'   => 1,
            ], [
                'id'         => 4,
                'type'       => 'image',
                'path'       => $this->productImages('product/4', 'product/4/4.webp'),
                'product_id' => 4,
                'position'   => 1,
            ],
        ]);

        DB::table('product_super_attributes')->insert([
            [
                'product_id'       => 1,
                'attribute_id'     => 1,
            ], [
                'product_id'       => 2,
                'attribute_id'     => 1,
            ], [
                'product_id'       => 3,
                'attribute_id'     => 1,
            ], [
                'product_id'       => 4,
                'attribute_id'     => 1,
            ],
        ]);

        DB::table('product_up_sells')->insert([
            [
                'parent_id'  => 1,
                'child_id'   => 4,
            ], [
                'parent_id'  => 2,
                'child_id'   => 1,
            ], [
                'parent_id'  => 3,
                'child_id'   => 4,
            ], [
                'parent_id'  => 4,
                'child_id'   => 3,
            ],
        ]);
    }

    /**
     * Store image in storage.
     *
     * @return string|null
     */
    public function productImages($targetPath, $file, $default = null)
    {
        if (file_exists(base_path(self::BASE_PATH.$file))) {
            return 'storage/'.Storage::putFile($targetPath, new File(base_path(self::BASE_PATH.$file)));
        }

        if (! $default) {
            return;
        }

        if (file_exists(base_path(self::BASE_PATH.$default))) {
            return 'storage/'.Storage::putFile($targetPath, new File(base_path(self::BASE_PATH.$default)));
        }
    }
}
