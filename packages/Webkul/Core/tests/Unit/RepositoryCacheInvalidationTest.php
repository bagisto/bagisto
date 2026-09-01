<?php

use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Storage;
use Prettus\Repository\Events\RepositoryEntityCreated;
use Prettus\Repository\Events\RepositoryEntityUpdated;
use Webkul\Core\Models\Locale;
use Webkul\Core\Repositories\ChannelRepository;
use Webkul\Core\Repositories\LocaleRepository;

it('does not serve a newly created channel without the image it was given', function () {
    Storage::fake();

    $repository = app(ChannelRepository::class);

    $existing = $repository->find(1);

    app()->instance('request', Request::create('/', 'POST', [], [], [
        'logo' => [UploadedFile::fake()->image('channel-logo.png')],
    ]));

    Event::listen(RepositoryEntityCreated::class, function () use ($repository) {
        $repository->all();
    });

    $channel = $repository->create([
        'code' => 'probe'.random_int(1000, 9999),
        'name' => 'Probe channel',
        'hostname' => 'probe-'.random_int(1000, 9999).'.test',
        'root_category_id' => $existing->root_category_id,
        'default_locale_id' => $existing->default_locale_id,
        'base_currency_id' => $existing->base_currency_id,
        'locales' => [$existing->default_locale_id],
        'currencies' => [$existing->base_currency_id],
        'inventory_sources' => $existing->inventory_sources->pluck('id')->all(),
    ]);

    expect($repository->all()->firstWhere('id', $channel->id)->logo)->not->toBeNull();
});

it('does not serve a channel image the update already removed', function () {
    $repository = app(ChannelRepository::class);

    $channel = $repository->find(1);

    $channel->logo = 'channel/1/before.png';

    $channel->save();

    Event::listen(RepositoryEntityUpdated::class, function () use ($repository) {
        $repository->all();
    });

    $repository->update([
        'code' => $channel->code,
        'name' => $channel->name,
        'hostname' => $channel->hostname,
        'root_category_id' => $channel->root_category_id,
        'default_locale_id' => $channel->default_locale_id,
        'base_currency_id' => $channel->base_currency_id,
        'locales' => [$channel->default_locale_id],
        'currencies' => [$channel->base_currency_id],
        'inventory_sources' => $channel->inventory_sources->pluck('id')->all(),
    ], 1);

    expect($repository->all()->firstWhere('id', 1)->logo)->toBeNull();
});

it('does not serve a newly created locale without the image it was given', function () {
    Storage::fake();

    $repository = app(LocaleRepository::class);

    Event::listen(RepositoryEntityCreated::class, function () use ($repository) {
        $repository->all();
    });

    $locale = $repository->create([
        'code' => 'xx'.random_int(1000, 9999),
        'name' => 'Probe locale',
        'direction' => 'ltr',
        'logo_path' => [UploadedFile::fake()->image('locale-logo.png')],
    ]);

    expect($repository->all()->firstWhere('id', $locale->id)->logo_path)->not->toBeNull();
});

it('does not serve a locale image the update already removed', function () {
    $repository = app(LocaleRepository::class);

    $locale = Locale::factory()->create(['logo_path' => 'locales/before.png']);

    Event::listen(RepositoryEntityUpdated::class, function () use ($repository) {
        $repository->all();
    });

    $repository->update([
        'code' => $locale->code,
        'name' => $locale->name,
        'direction' => $locale->direction,
    ], $locale->id);

    expect($repository->all()->firstWhere('id', $locale->id)->logo_path)->toBeNull();
});
