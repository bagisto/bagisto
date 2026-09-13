<?php

namespace Webkul\Core\Tests\Concerns;

use Webkul\Core\Models\CoreConfig;

trait ConfiguresSettings
{
    /**
     * Save admin settings for the channel and locale the test runs in, by code or as a code-to-value map.
     *
     * A setting is read at the scope its field declares, and a row saved at another scope is never seen,
     * so every other row of the code is dropped first and the new one carries both the channel and the
     * locale, which satisfies whichever scope the field is read at.
     */
    public function setConfig(array|string $settings, mixed $value = null): void
    {
        if (is_string($settings)) {
            $settings = [$settings => $value];
        }

        foreach ($settings as $code => $value) {
            CoreConfig::query()->where('code', $code)->delete();

            CoreConfig::query()->create([
                'code' => $code,
                'value' => $value,
                'channel_code' => core()->getCurrentChannelCode(),
                'locale_code' => core()->getCurrentLocale()->code,
            ]);
        }
    }
}
