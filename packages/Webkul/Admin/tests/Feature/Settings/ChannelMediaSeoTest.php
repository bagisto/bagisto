<?php

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Webkul\Core\Models\Channel;

use function Pest\Laravel\get;
use function Pest\Laravel\putJson;

function makeChannelWithLogo(): Channel
{
    $channel = Channel::factory()->create();

    $channel->logo = 'channel/'.$channel->id.'/mn73kdla.png';

    $channel->favicon = 'channel/'.$channel->id.'/qp02shwe.ico';

    $channel->save();

    Storage::put($channel->logo, 'logo-contents');

    Storage::put($channel->favicon, 'favicon-contents');

    return $channel;
}

function channelUpdatePayload(Channel $channel, array $extra = []): array
{
    return array_merge([
        'code' => $channel->code,

        app()->getLocale() => [
            'name' => fake()->name(),
            'seo_title' => fake()->title(),
            'seo_description' => substr(fake()->paragraph(), 0, 50),
            'seo_keywords' => fake()->name(),
            'description' => substr(fake()->paragraph(), 0, 50),
        ],

        'hostname' => 'http://'.fake()->ipv4(),
        'root_category_id' => 1,
        'default_locale_id' => 1,
        'base_currency_id' => 1,
        'inventory_sources' => [1],
        'locales' => [1],
        'currencies' => [1],
    ], $extra);
}

it('should render the seo drawer on the channel edit page', function () {
    $channel = makeChannelWithLogo();

    $this->loginAsAdmin();

    get(route('admin.settings.channels.edit', $channel->id))
        ->assertOk()
        ->assertSee('logo_meta')
        ->assertSee('favicon_meta');
});

it('should save the alt text of the channel logo', function () {
    Storage::fake();

    $channel = makeChannelWithLogo();

    $this->loginAsAdmin();

    putJson(route('admin.settings.channels.update', $channel->id), channelUpdatePayload($channel, [
        'logo' => ['logo' => ''],
        'logo_meta' => ['logo' => ['alt_text' => 'Acme Store']],
        'favicon' => ['favicon' => ''],
    ]))->assertRedirect(route('admin.settings.channels.index'));

    expect($channel->fresh()->logo_alt)->toBe('Acme Store');
});

it('should rename the channel logo while keeping its extension', function () {
    Storage::fake();

    $channel = makeChannelWithLogo();

    $originalPath = $channel->logo;

    $this->loginAsAdmin();

    putJson(route('admin.settings.channels.update', $channel->id), channelUpdatePayload($channel, [
        'logo' => ['logo' => ''],
        'logo_meta' => ['logo' => ['file_name' => 'Acme Store Logo']],
        'favicon' => ['favicon' => ''],
    ]))->assertRedirect(route('admin.settings.channels.index'));

    $expected = 'channel/'.$channel->id.'/acme-store-logo.png';

    expect($channel->fresh()->logo)->toBe($expected);

    Storage::assertExists($expected);

    Storage::assertMissing($originalPath);
});

it('should rename the channel favicon while keeping its ico extension', function () {
    Storage::fake();

    $channel = makeChannelWithLogo();

    $this->loginAsAdmin();

    putJson(route('admin.settings.channels.update', $channel->id), channelUpdatePayload($channel, [
        'logo' => ['logo' => ''],
        'favicon' => ['favicon' => ''],
        'favicon_meta' => ['favicon' => ['file_name' => 'Acme Favicon']],
    ]))->assertRedirect(route('admin.settings.channels.index'));

    expect($channel->fresh()->favicon)->toBe('channel/'.$channel->id.'/acme-favicon.ico');
});

it('should name a newly uploaded channel logo after the requested file name', function () {
    Storage::fake();

    $channel = Channel::factory()->create();

    $this->loginAsAdmin();

    putJson(route('admin.settings.channels.update', $channel->id), channelUpdatePayload($channel, [
        'logo' => ['image_0' => UploadedFile::fake()->image('DSC_0003.png', 20, 20)],
        'logo_meta' => ['image_0' => ['alt_text' => 'Acme Store', 'file_name' => 'Acme Store Logo']],
    ]))->assertRedirect(route('admin.settings.channels.index'));

    $channel = $channel->fresh();

    expect($channel->logo)->toBe('channel/'.$channel->id.'/acme-store-logo.png');

    expect($channel->logo_alt)->toBe('Acme Store');

    Storage::assertExists($channel->logo);
});

it('should reject a channel alt text longer than the column allows', function () {
    Storage::fake();

    $channel = makeChannelWithLogo();

    $this->loginAsAdmin();

    putJson(route('admin.settings.channels.update', $channel->id), channelUpdatePayload($channel, [
        'logo' => ['logo' => ''],
        'logo_meta' => ['logo' => ['alt_text' => str_repeat('a', 256)]],
    ]))->assertUnprocessable();
});

it('should drop the channel logo alt text when the logo is removed', function () {
    Storage::fake();

    $channel = makeChannelWithLogo();

    $channel->translateOrNew(app()->getLocale())->logo_alt = 'Acme Store';

    $channel->save();

    $this->loginAsAdmin();

    putJson(route('admin.settings.channels.update', $channel->id), channelUpdatePayload($channel, [
        'favicon' => ['favicon' => ''],
    ]))->assertRedirect(route('admin.settings.channels.index'));

    $channel = $channel->fresh();

    expect($channel->logo)->toBeNull();

    expect($channel->logo_alt)->toBeNull();
});
