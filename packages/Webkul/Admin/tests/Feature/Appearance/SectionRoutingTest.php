<?php

use Webkul\Theme\Models\Section;

use function Pest\Laravel\get;
use function Pest\Laravel\getJson;

it('should render the customize screen at its new location', function () {
    $this->loginAsAdmin();

    get(route('admin.appearance.sections.index', ['code' => core()->getCurrentChannel()->theme]))
        ->assertOk()
        ->assertSee(trans('admin::app.components.layouts.sidebar.sections'));
});

it('should say which theme the listing is scoped to', function () {
    $this->loginAsAdmin();

    get(route('admin.appearance.sections.index', ['code' => 'default']))
        ->assertOk()
        ->assertSee(config('themes.shop.default.name'));
});

it('should no longer answer on the settings theme urls', function () {
    $routes = app('router')->getRoutes();

    foreach (['index', 'store', 'edit', 'update', 'delete'] as $action) {
        expect($routes->getByName('admin.settings.themes.'.$action))->toBeNull();
    }

    $this->loginAsAdmin();

    get('/'.config('app.admin_url').'/themes')->assertNotFound();
});

it('should no longer offer a section picker on the channel form', function () {
    $channel = core()->getCurrentChannel();

    $this->loginAsAdmin();

    get(route('admin.settings.channels.edit', $channel->id))
        ->assertOk()
        ->assertSee(route('admin.appearance.themes.index'))
        ->assertDontSee('id="theme"', false);
});

it('should scope the section listing to a theme when one is requested', function () {
    $channel = core()->getCurrentChannel();

    Section::factory()->create([
        'channel_id' => $channel->id,
        'theme_code' => 'default',
        'name' => 'Belongs To Default',
    ]);

    Section::factory()->create([
        'channel_id' => $channel->id,
        'theme_code' => 'some-other-theme',
        'name' => 'Belongs To Another',
    ]);

    $this->loginAsAdmin();

    getJson(route('admin.appearance.sections.index', ['code' => 'default']), ['X-Requested-With' => 'XMLHttpRequest'])
        ->assertOk()
        ->assertSee('Belongs To Default')
        ->assertDontSee('Belongs To Another');
});

it('should list the sections of the channel current theme', function () {
    $channel = core()->getCurrentChannel();

    Section::factory()->create([
        'channel_id' => $channel->id,
        'theme_code' => $channel->theme,
        'name' => 'Belongs To Current Theme',
    ]);

    $this->loginAsAdmin();

    get(route('admin.appearance.sections.index', ['code' => core()->getCurrentChannel()->theme]))
        ->assertOk()
        ->assertSee('Belongs To Current Theme');
});

it('should not found a theme this installation does not have', function () {
    $this->loginAsAdmin();

    get(route('admin.appearance.sections.index', ['code' => 'not-a-theme']))
        ->assertNotFound();
});

it('should nest the section listing under its theme', function () {
    expect(route('admin.appearance.sections.index', ['code' => 'default'], false))
        ->toBe('/admin/appearance/themes/default/sections');
});

it('should not offer sections in the sidebar', function () {
    $keys = collect(config('menu.admin'))->pluck('key');

    expect($keys)->toContain('appearance.themes');

    expect($keys)->not->toContain('appearance.sections');
});
