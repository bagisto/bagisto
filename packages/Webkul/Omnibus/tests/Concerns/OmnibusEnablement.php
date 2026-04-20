<?php

namespace Webkul\Omnibus\Tests\Concerns;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

trait OmnibusEnablement
{
    /**
     * Toggle the Omnibus is_enabled setting for the current channel.
     *
     * Writes directly to the core_config table — the same row the admin
     * settings page creates — and flushes the Prettus repository cache so
     * CoreConfigRepository picks the new value up.
     */
    protected function setOmnibusEnabled(bool $enabled): void
    {
        $channelCode = core()->getCurrentChannel()->code;

        DB::table('core_config')
            ->where('code', 'catalog.products.omnibus.is_enabled')
            ->where('channel_code', $channelCode)
            ->delete();

        if ($enabled) {
            DB::table('core_config')->insert([
                'code' => 'catalog.products.omnibus.is_enabled',
                'value' => '1',
                'channel_code' => $channelCode,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        Cache::flush();
    }
}
