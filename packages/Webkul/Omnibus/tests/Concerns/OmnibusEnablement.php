<?php

namespace Webkul\Omnibus\Tests\Concerns;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

trait OmnibusEnablement
{
    /**
     * Toggle the Omnibus is_enabled setting for the current channel.
     *
     * Writes an explicit '1' or '0' to the core_config row for the current
     * channel — the same row the admin settings page creates — so the test's
     * desired state wins over whatever fallback lives in config or env.
     * Flushes the Prettus repository cache so CoreConfigRepository picks the
     * new value up.
     */
    protected function setOmnibusEnabled(bool $enabled): void
    {
        $channelCode = core()->getCurrentChannel()->code;

        DB::table('core_config')
            ->where('code', 'catalog.products.omnibus.is_enabled')
            ->where('channel_code', $channelCode)
            ->delete();

        DB::table('core_config')->insert([
            'code' => 'catalog.products.omnibus.is_enabled',
            'value' => $enabled ? '1' : '0',
            'channel_code' => $channelCode,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        Cache::flush();
    }
}
