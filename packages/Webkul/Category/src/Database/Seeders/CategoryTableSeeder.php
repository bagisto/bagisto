<?php

namespace Webkul\Category\Database\Seeders;

use Illuminate\Database\Seeder;
use DB;
use Carbon\Carbon;

class CategoryTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('categories')->delete();

        $now = Carbon::now();

        DB::table('categories')->insert([
            ['id' => '1','position' => '1','image' => NULL,'status' => '1','_lft' => '1','_rgt' => '14','parent_id' => NULL, 'created_at' => $now, 'updated_at' => $now],
            ['id' => '2','position' => '1','image' => NULL,'status' => '1','_lft' => '2','_rgt' => '7','parent_id' => '1', 'created_at' => $now, 'updated_at' => $now],
            ['id' => '3','position' => '2','image' => NULL,'status' => '1','_lft' => '8','_rgt' => '13','parent_id' => '1', 'created_at' => $now, 'updated_at' => $now],
            ['id' => '4','position' => '1','image' => NULL,'status' => '1','_lft' => '5','_rgt' => '6','parent_id' => '2', 'created_at' => $now, 'updated_at' => $now],
            ['id' => '5','position' => '2','image' => NULL,'status' => '1','_lft' => '3','_rgt' => '4','parent_id' => '2', 'created_at' => $now, 'updated_at' => $now],
            ['id' => '6','position' => '1','image' => NULL,'status' => '1','_lft' => '9','_rgt' => '10','parent_id' => '3', 'created_at' => $now, 'updated_at' => $now],
            ['id' => '7','position' => '2','image' => NULL,'status' => '1','_lft' => '11','_rgt' => '12','parent_id' => '3', 'created_at' => $now, 'updated_at' => $now]
        ]);

        DB::table('category_translations')->insert([
            ['id' => '1','name' => 'Root','slug' => 'root','description' => 'Root','meta_title' => '','meta_description' => '','meta_keywords' => '','category_id' => '1','locale' => 'en'],
            ['id' => '2','name' => 'Women','slug' => 'women','description' => 'Women','meta_title' => '','meta_description' => '','meta_keywords' => '','category_id' => '2','locale' => 'en'],
            ['id' => '3','name' => 'Men','slug' => 'men','description' => 'Men','meta_title' => '','meta_description' => '','meta_keywords' => '','category_id' => '3','locale' => 'en'],
            ['id' => '4','name' => 'Tops','slug' => 'tops-women','description' => 'Tops','meta_title' => '','meta_description' => '','meta_keywords' => '','category_id' => '4','locale' => 'en'],
            ['id' => '5','name' => 'Bottoms','slug' => 'bottoms-women','description' => 'Bottoms','meta_title' => '','meta_description' => '','meta_keywords' => '','category_id' => '5','locale' => 'en'],
            ['id' => '6','name' => 'Tops','slug' => 'tops-men','description' => 'Tops','meta_title' => '','meta_description' => '','meta_keywords' => '','category_id' => '6','locale' => 'en'],
            ['id' => '7','name' => 'Bottoms','slug' => 'bottoms-men','description' => 'Bottoms','meta_title' => '','meta_description' => '','meta_keywords' => '','category_id' => '7','locale' => 'en']
        ]);
    }
}