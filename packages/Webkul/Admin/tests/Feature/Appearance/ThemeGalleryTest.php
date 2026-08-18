<?php

use Illuminate\Support\Facades\Event;
use Webkul\Core\Models\Channel;
use Webkul\Core\Repositories\ChannelRepository;
use Webkul\Theme\Models\Section;
use Webkul\Theme\ThemeCatalog;

use function Pest\Laravel\get;
use function Pest\Laravel\getJson;
use function Pest\Laravel\postJson;

it('should render the theme gallery', function () {
    $this->loginAsAdmin();

    get(route('admin.appearance.themes.index'))
        ->assertOk()
        ->assertSee(trans('admin::app.appearance.themes.index.title'))
        ->assertSee('Default');
});

it('should mark the theme a channel runs as active', function () {
    $channel = core()->getCurrentChannel();

    $channel->theme = 'default';

    $channel->save();

    $theme = app(ThemeCatalog::class)->find('default');

    expect($theme['status'])->toBe(ThemeCatalog::STATUS_ACTIVE);

    expect(collect($theme['active_on'])->pluck('id'))->toContain($channel->id);
});

it('should list catalog themes that are not installed as available', function () {
    $theme = app(ThemeCatalog::class)->find('ethereal-fashion');

    expect($theme)->not->toBeNull();

    expect($theme['status'])->toBe(ThemeCatalog::STATUS_AVAILABLE);

    expect($theme['is_installed'])->toBeFalse();

    expect($theme['url'])->not->toBeNull();
});

it('should sort active themes ahead of purchasable ones', function () {
    $statuses = app(ThemeCatalog::class)->all()->pluck('status')->unique()->values()->toArray();

    expect($statuses[0])->toBeIn([ThemeCatalog::STATUS_ACTIVE, ThemeCatalog::STATUS_INSTALLED]);

    expect(end($statuses))->toBe(ThemeCatalog::STATUS_AVAILABLE);
});

it('should report how many customizations a theme switch would leave behind', function () {
    $channel = Channel::factory()->create(['theme' => 'default']);

    Section::factory()->count(3)->create([
        'channel_id' => $channel->id,
        'theme_code' => 'default',
    ]);

    $this->loginAsAdmin();

    getJson(route('admin.appearance.themes.impact', 'ethereal-fashion').'?channel_ids[]='.$channel->id)
        ->assertOk()
        ->assertJsonPath('impact.0.channel_id', $channel->id)
        ->assertJsonPath('impact.0.current_theme', config('themes.shop.default.name'))
        ->assertJsonPath('impact.0.customizations', 3);
});

it('should activate an installed theme on a channel', function () {
    $channel = Channel::factory()->create(['theme' => 'something-else']);

    $this->loginAsAdmin();

    postJson(route('admin.appearance.themes.activate', 'default'), [
        'channel_ids' => [$channel->id],
    ])->assertOk();

    expect($channel->fresh()->theme)->toBe('default');
});

it('should announce a channel update so the channel and page caches are dropped', function () {
    $channel = Channel::factory()->create(['theme' => 'something-else']);

    Event::fake();

    $this->loginAsAdmin();

    postJson(route('admin.appearance.themes.activate', 'default'), [
        'channel_ids' => [$channel->id],
    ])->assertOk();

    Event::assertDispatched('core.channel.update.before');

    Event::assertDispatched('core.channel.update.after');
});

it('should read back the newly activated theme rather than a cached one', function () {
    $channel = Channel::factory()->create(['theme' => 'something-else']);

    app(ChannelRepository::class)->find($channel->id);

    $this->loginAsAdmin();

    postJson(route('admin.appearance.themes.activate', 'default'), [
        'channel_ids' => [$channel->id],
    ])->assertOk();

    expect(app(ChannelRepository::class)->find($channel->id)->theme)->toBe('default');
});

it('should refuse to activate a theme that is not installed', function () {
    $channel = Channel::factory()->create(['theme' => 'default']);

    $this->loginAsAdmin();

    postJson(route('admin.appearance.themes.activate', 'ethereal-fashion'), [
        'channel_ids' => [$channel->id],
    ])->assertNotFound();

    expect($channel->fresh()->theme)->toBe('default');
});

it('should reject an activation without a valid channel', function () {
    $this->loginAsAdmin();

    postJson(route('admin.appearance.themes.activate', 'default'), [
        'channel_ids' => [99999],
    ])->assertUnprocessable();
});

it('should activate a theme on several channels at once', function () {
    $first = Channel::factory()->create(['theme' => 'something-else']);

    $second = Channel::factory()->create(['theme' => 'something-else']);

    $this->loginAsAdmin();

    postJson(route('admin.appearance.themes.activate', 'default'), [
        'channel_ids' => [$first->id, $second->id],
    ])->assertOk();

    expect($first->fresh()->theme)->toBe('default');

    expect($second->fresh()->theme)->toBe('default');
});

it('should reject an activation with no channel selected', function () {
    $this->loginAsAdmin();

    postJson(route('admin.appearance.themes.activate', 'default'), [
        'channel_ids' => [],
    ])->assertUnprocessable();
});

it('should leave a channel already on the theme out of the impact report', function () {
    $channel = Channel::factory()->create(['theme' => 'default']);

    Section::factory()->count(2)->create([
        'channel_id' => $channel->id,
        'theme_code' => 'default',
    ]);

    $this->loginAsAdmin();

    getJson(route('admin.appearance.themes.impact', 'default').'?channel_ids[]='.$channel->id)
        ->assertOk()
        ->assertJsonCount(0, 'impact');
});

it('should not offer a buy button for a theme with no store page', function () {
    $theme = app(ThemeCatalog::class)->find('elvix');

    expect($theme['url'])->toBeNull();

    expect($theme['demo_url'])->not->toBeNull();
});

it('should not carry a price, which the store page is the source of', function () {
    foreach (app(ThemeCatalog::class)->all() as $theme) {
        expect($theme)->not->toHaveKeys(['price', 'currency', 'is_paid']);
    }
});

it('should carry the catalog details onto every marketplace theme', function () {
    $marketplace = app(ThemeCatalog::class)
        ->all()
        ->reject(fn ($theme) => $theme['is_installed']);

    expect($marketplace)->not->toBeEmpty();

    foreach ($marketplace as $theme) {
        expect($theme['name'])->not->toBeEmpty();

        expect($theme['description'])->not->toBeEmpty();

        expect($theme['screenshot'])->toStartWith('https://');

        expect($theme['demo_url'])->toStartWith('https://');
    }
});

it('should serve the bundled screenshot of an installed theme from the admin build', function () {
    $theme = app(ThemeCatalog::class)->find('default');

    expect($theme['screenshot'])->toContain('themes/admin/default/build/');

    expect($theme['screenshot'])->toEndWith('.jpg');
});
