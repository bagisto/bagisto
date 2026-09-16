<?php

use Webkul\Core\Models\Channel;
use Webkul\Core\Models\Locale;

beforeEach(function () {
    $this->channelLocale = Locale::factory()->create();

    $this->channel = Channel::factory()->create();

    $this->channel->locales()->sync([$this->channelLocale->id]);

    $this->channel->default_locale_id = $this->channelLocale->id;

    $this->channel->save();
});

// ============================================================================
// Locale Resolution
// ============================================================================

it('should keep a locale code the channel has', function () {
    expect($this->channel->resolveLocaleCode($this->channelLocale->code))->toBe($this->channelLocale->code);
});

it('should resolve a locale code the channel lacks to the channel default locale', function () {
    $otherLocale = Locale::factory()->create();

    expect($this->channel->resolveLocaleCode($otherLocale->code))->toBe($this->channelLocale->code);
});

it('should resolve a missing locale code to the channel default locale', function () {
    expect($this->channel->resolveLocaleCode(null))->toBe($this->channelLocale->code);
});
