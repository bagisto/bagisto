<?php

use Webkul\Theme\Models\Section;

use function Pest\Laravel\get;
use function Pest\Laravel\getJson;

it('should render the customize screen at its new location', function () {
    $this->loginAsAdmin();

    get(route('admin.appearance.sections.index'))
        ->assertOk()
        ->assertSee(trans('admin::app.components.layouts.sidebar.sections'));
});

it('should say which theme the listing is scoped to', function () {
    $this->loginAsAdmin();

    get(route('admin.appearance.sections.index', ['theme' => 'default']))
        ->assertOk()
        ->assertSee(trans('admin::app.appearance.sections.index.show-all'))
        ->assertSee(config('themes.shop.default.name'));
});

it('should not claim a scope when every section is listed', function () {
    $this->loginAsAdmin();

    get(route('admin.appearance.sections.index'))
        ->assertOk()
        ->assertDontSee(trans('admin::app.appearance.sections.index.show-all'));
});

it('should redirect the legacy themes url to customize', function () {
    $this->loginAsAdmin();

    get(route('admin.settings.themes.index'))
        ->assertStatus(301)
        ->assertRedirect(route('admin.appearance.sections.index'));
});

it('should redirect the legacy theme edit url to customize', function () {
    $section = Section::factory()->create();

    $this->loginAsAdmin();

    get(route('admin.settings.themes.edit', $section->id))
        ->assertStatus(301)
        ->assertRedirect(route('admin.appearance.sections.edit', $section->id));
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

    getJson(route('admin.appearance.sections.index', ['theme' => 'default']), ['X-Requested-With' => 'XMLHttpRequest'])
        ->assertOk()
        ->assertSee('Belongs To Default')
        ->assertDontSee('Belongs To Another');
});

it('should list every section when no theme is requested', function () {
    $channel = core()->getCurrentChannel();

    Section::factory()->create([
        'channel_id' => $channel->id,
        'theme_code' => 'some-other-theme',
        'name' => 'Belongs To Another',
    ]);

    $this->loginAsAdmin();

    getJson(route('admin.appearance.sections.index'), ['X-Requested-With' => 'XMLHttpRequest'])
        ->assertOk()
        ->assertSee('Belongs To Another');
});

it('should ignore an unknown theme rather than hiding everything', function () {
    $channel = core()->getCurrentChannel();

    Section::factory()->create([
        'channel_id' => $channel->id,
        'theme_code' => 'default',
        'name' => 'Belongs To Default',
    ]);

    $this->loginAsAdmin();

    getJson(route('admin.appearance.sections.index', ['theme' => 'not-a-theme']), ['X-Requested-With' => 'XMLHttpRequest'])
        ->assertOk()
        ->assertSee('Belongs To Default');
});

it('should not offer sections in the sidebar', function () {
    $keys = collect(config('menu.admin'))->pluck('key');

    expect($keys)->toContain('appearance.themes');

    expect($keys)->not->toContain('appearance.sections');
});
