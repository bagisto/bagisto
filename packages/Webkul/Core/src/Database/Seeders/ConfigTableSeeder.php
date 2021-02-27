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
            'id'           => 1,
            'code'         => 'catalog.products.guest-checkout.allow-guest-checkout',
            'value'        => '1',
            'channel_code' => null,
            'locale_code'  => null,
            'created_at'   => $now,
            'updated_at'   => $now,
        ]);

        DB::table('core_config')->insert([
            'id'           => 2,
            'code'         => 'emails.general.notifications.emails.general.notifications.verification',
            'value'        => '1',
            'channel_code' => null,
            'locale_code'  => null,
            'created_at'   => $now,
            'updated_at'   => $now,
        ]);

        DB::table('core_config')->insert([
            'id'           => 3,
            'code'         => 'emails.general.notifications.emails.general.notifications.registration',
            'value'        => '1',
            'channel_code' => null,
            'locale_code'  => null,
            'created_at'   => $now,
            'updated_at'   => $now,
        ]);

        DB::table('core_config')->insert([
            'id'           => 4,
            'code'         => 'emails.general.notifications.emails.general.notifications.customer',
            'value'        => '1',
            'channel_code' => null,
            'locale_code'  => null,
            'created_at'   => $now,
            'updated_at'   => $now,
        ]);

        DB::table('core_config')->insert([
            'id'           => 5,
            'code'         => 'emails.general.notifications.emails.general.notifications.new-order',
            'value'        => '1',
            'channel_code' => null,
            'locale_code'  => null,
            'created_at'   => $now,
            'updated_at'   => $now,
        ]);

        DB::table('core_config')->insert([
            'id'           => 6,
            'code'         => 'emails.general.notifications.emails.general.notifications.new-admin',
            'value'        => '1',
            'channel_code' => null,
            'locale_code'  => null,
            'created_at'   => $now,
            'updated_at'   => $now,
        ]);

        DB::table('core_config')->insert([
            'id'           => 7,
            'code'         => 'emails.general.notifications.emails.general.notifications.new-invoice',
            'value'        => '1',
            'channel_code' => null,
            'locale_code'  => null,
            'created_at'   => $now,
            'updated_at'   => $now,
        ]);

        DB::table('core_config')->insert([
            'id'           => 8,
            'code'         => 'emails.general.notifications.emails.general.notifications.new-refund',
            'value'        => '1',
            'channel_code' => null,
            'locale_code'  => null,
            'created_at'   => $now,
            'updated_at'   => $now,
        ]);

        DB::table('core_config')->insert([
            'id'           => 9,
            'code'         => 'emails.general.notifications.emails.general.notifications.new-shipment',
            'value'        => '1',
            'channel_code' => null,
            'locale_code'  => null,
            'created_at'   => $now,
            'updated_at'   => $now,
        ]);

        DB::table('core_config')->insert([
            'id'           => 10,
            'code'         => 'emails.general.notifications.emails.general.notifications.new-inventory-source',
            'value'        => '1',
            'channel_code' => null,
            'locale_code'  => null,
            'created_at'   => $now,
            'updated_at'   => $now,
        ]);

        DB::table('core_config')->insert([
            'id'           => 11,
            'code'         => 'emails.general.notifications.emails.general.notifications.cancel-order',
            'value'        => '1',
            'channel_code' => null,
            'locale_code'  => null,
            'created_at'   => $now,
            'updated_at'   => $now,
        ]);

        DB::table('core_config')->insert([
            'id'           => 12,
            'code'         => 'catalog.products.homepage.out_of_stock_items',
            'value'        => '1',
            'channel_code' => null,
            'locale_code'  => null,
            'created_at'   => $now,
            'updated_at'   => $now,
        ]);
    }
}