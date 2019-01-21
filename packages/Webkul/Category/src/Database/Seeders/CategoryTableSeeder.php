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
            ['id' => '1','position' => '1','image' => NULL,'status' => '1','_lft' => '1','_rgt' => '14','parent_id' => NULL, 'created_at' => $now, 'updated_at' => $now]
        ]);

        DB::table('category_translations')->insert([
            ['id' => '1','name' => 'Root','slug' => 'root','description' => 'Root','meta_title' => '','meta_description' => '','meta_keywords' => '','category_id' => '1','locale' => 'en']
        ]);
    }
}