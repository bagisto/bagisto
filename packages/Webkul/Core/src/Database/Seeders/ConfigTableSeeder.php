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
            'code'         => 'catalog.products.guest_checkout.allow_guest_checkout',
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
            'code'         => 'emails.general.notifications.emails.general.notifications.new_order',
            'value'        => '1',
            'channel_code' => null,
            'locale_code'  => null,
            'created_at'   => $now,
            'updated_at'   => $now,
        ]);

        DB::table('core_config')->insert([
            'id'           => 6,
            'code'         => 'emails.general.notifications.emails.general.notifications.new_admin',
            'value'        => '1',
            'channel_code' => null,
            'locale_code'  => null,
            'created_at'   => $now,
            'updated_at'   => $now,
        ]);

        DB::table('core_config')->insert([
            'id'           => 7,
            'code'         => 'emails.general.notifications.emails.general.notifications.new_invoice',
            'value'        => '1',
            'channel_code' => null,
            'locale_code'  => null,
            'created_at'   => $now,
            'updated_at'   => $now,
        ]);

        DB::table('core_config')->insert([
            'id'           => 8,
            'code'         => 'emails.general.notifications.emails.general.notifications.new_refund',
            'value'        => '1',
            'channel_code' => null,
            'locale_code'  => null,
            'created_at'   => $now,
            'updated_at'   => $now,
        ]);

        DB::table('core_config')->insert([
            'id'           => 9,
            'code'         => 'emails.general.notifications.emails.general.notifications.new_shipment',
            'value'        => '1',
            'channel_code' => null,
            'locale_code'  => null,
            'created_at'   => $now,
            'updated_at'   => $now,
        ]);

        DB::table('core_config')->insert([
            'id'           => 10,
            'code'         => 'emails.general.notifications.emails.general.notifications.new_inventory_source',
            'value'        => '1',
            'channel_code' => null,
            'locale_code'  => null,
            'created_at'   => $now,
            'updated_at'   => $now,
        ]);

        DB::table('core_config')->insert([
            'id'           => 11,
            'code'         => 'emails.general.notifications.emails.general.notifications.cancel_order',
            'value'        => '1',
            'channel_code' => null,
            'locale_code'  => null,
            'created_at'   => $now,
            'updated_at'   => $now,
        ]);
    }
}