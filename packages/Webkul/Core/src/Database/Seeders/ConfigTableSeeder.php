<?php

namespace Webkul\Core\Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ConfigTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('core_config')->delete();

        $now = Carbon::now();

        DB::table('core_config')->insert([
            'id' => 1,
            'code' => 'catalog.products.guest-checkout.allow-guest-checkout',
            'value' => '1',
            'channel_code' => null,
            'locale_code' => null,
            'created_at' => $now,
            'updated_at' => $now
        ]);
    }
}