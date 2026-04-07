<?php

use Illuminate\Http\UploadedFile;
use Webkul\Theme\Models\ThemeCustomization;

use function Pest\Laravel\deleteJson;
use function Pest\Laravel\get;
use function Pest\Laravel\postJson;

// ============================================================================
// Index
// ============================================================================

it('should return the theme index page', function () {
    $this->loginAsAdmin();

    get(route('admin.settings.themes.index'))
        ->assertOk()
        ->assertSeeText(trans('admin::app.settings.themes.index.title'))
        ->assertSeeText(trans('admin::app.settings.themes.index.create-btn'));
});

it('should deny guest access to the theme index page', function () {
    get(route('admin.settings.themes.index'))
        ->assertRedirect(route('admin.session.create'));
});

// ============================================================================
// Store
// ============================================================================

it('should store a newly created theme', function () {
    $lastThemeId = ThemeCustomization::factory()->create()->id + 1;

    $this->loginAsAdmin();

    postJson(route('admin.settings.themes.store'), [
        'type' => $type = fake()->randomElement([
            'product_carousel',
            'category_carousel',
            'image_carousel',
            'footer_links',
            'services_content',
        ]),
        'name' => $name = fake()->name(),
        'sort_order' => $lastThemeId,
        'channel_id' => core()->getCurrentChannel()->id,
        'theme_code' => core()->getCurrentChannel()->theme,
    ])
        ->assertOk()
        ->assertJsonPath('redirect_url', route('admin.settings.themes.edit', $lastThemeId));

    $this->assertDatabaseHas('theme_customizations', [
        'id' => $lastThemeId,
        'type' => $type,
        'name' => $name,
    ]);
});

it('should fail validation when required fields are missing on store', function () {
    $this->loginAsAdmin();

    postJson(route('admin.settings.themes.store'))
        ->assertUnprocessable()
        ->assertJsonValidationErrorFor('name')
        ->assertJsonValidationErrorFor('sort_order')
        ->assertJsonValidationErrorFor('type')
        ->assertJsonValidationErrorFor('channel_id')
        ->assertJsonValidationErrorFor('theme_code');
});

it('should fail validation when an invalid type is provided on store', function () {
    $this->loginAsAdmin();

    postJson(route('admin.settings.themes.store'), [
        'type' => 'INVALID_TYPE',
    ])
        ->assertUnprocessable()
        ->assertJsonValidationErrorFor('type');
});

// ============================================================================
// Update
// ============================================================================

it('should update a theme customization', function () {
    $theme = ThemeCustomization::factory()->create();

    $data = [];

    switch ($theme->type) {
        case ThemeCustomization::PRODUCT_CAROUSEL:
            $data[app()->getLocale()] = [
                'options' => [
                    'title' => fake()->title(),
                    'filters' => ['sort' => 'name-desc', 'limit' => '12', 'new' => '1'],
                ],
            ];

            break;

        case ThemeCustomization::CATEGORY_CAROUSEL:
            $data[app()->getLocale()] = [
                'options' => [
                    'title' => fake()->title(),
                    'filters' => ['sort' => 'desc', 'limit' => '10', 'parent_id' => '1'],
                ],
            ];

            break;

        case ThemeCustomization::IMAGE_CAROUSEL:
            $data[app()->getLocale()] = [
                'options' => [
                    [
                        'title' => fake()->title(),
                        'link' => fake()->url(),
                        'image' => UploadedFile::fake()->image(fake()->word().'.png', 640, 480, 'png'),
                    ],
                ],
            ];

            break;

        case ThemeCustomization::FOOTER_LINKS:
            $data[app()->getLocale()] = [
                'options' => [
                    'column_1' => [
                        ['url' => fake()->url(), 'title' => fake()->title(), 'sort_order' => '1'],
                    ],
                ],
            ];

            break;

        case ThemeCustomization::SERVICES_CONTENT:
            $data[app()->getLocale()] = [
                'options' => [
                    [
                        'title' => fake()->title(),
                        'description' => fake()->paragraph(),
                        'service_icon' => 'icon-truck',
                    ],
                ],
            ];

            break;
    }

    $data['locale'] = app()->getLocale();
    $data['type'] = $theme->type;
    $data['name'] = $name = fake()->name();
    $data['sort_order'] = '1';
    $data['channel_id'] = core()->getCurrentChannel()->id;
    $data['theme_code'] = core()->getCurrentChannel()->theme;
    $data['status'] = 'on';

    $this->loginAsAdmin();

    postJson(route('admin.settings.themes.update', $theme->id), $data)
        ->assertRedirect(route('admin.settings.themes.index'));

    $this->assertDatabaseHas('theme_customizations', [
        'id' => $theme->id,
        'type' => $theme->type,
        'name' => $name,
    ]);
});

it('should fail validation when required fields are missing on update', function () {
    $theme = ThemeCustomization::factory()->create();

    $this->loginAsAdmin();

    postJson(route('admin.settings.themes.update', $theme->id))
        ->assertUnprocessable()
        ->assertJsonValidationErrorFor('name')
        ->assertJsonValidationErrorFor('sort_order')
        ->assertJsonValidationErrorFor('type')
        ->assertJsonValidationErrorFor('channel_id')
        ->assertJsonValidationErrorFor('theme_code');
});

// ============================================================================
// Delete
// ============================================================================

it('should delete a theme', function () {
    $theme = ThemeCustomization::factory()->create();

    $this->loginAsAdmin();

    deleteJson(route('admin.settings.themes.delete', $theme->id))
        ->assertOk()
        ->assertJsonPath('message', trans('admin::app.settings.themes.delete-success'));

    $this->assertDatabaseMissing('theme_customizations', ['id' => $theme->id]);
    $this->assertDatabaseMissing('theme_customization_translations', ['theme_customization_id' => $theme->id]);
});

// ============================================================================
// Static Content Security
// ============================================================================

it('should sanitize malicious script tags from static content', function () {
    $theme = ThemeCustomization::factory()->create(['type' => 'static_content']);

    $this->loginAsAdmin();

    postJson(route('admin.settings.themes.update', $theme->id), [
        app()->getLocale() => [
            'options' => [
                'html' => '<div>Safe content</div><script>alert("XSS")</script><p>More safe content</p>',
                'css' => 'body { color: red; }',
            ],
        ],
        'locale' => app()->getLocale(),
        'type' => 'static_content',
        'name' => fake()->name(),
        'sort_order' => '1',
        'channel_id' => core()->getCurrentChannel()->id,
        'theme_code' => core()->getCurrentChannel()->theme,
        'status' => 'on',
    ])
        ->assertRedirect(route('admin.settings.themes.index'));

    $theme->refresh();
    $translation = $theme->translate(app()->getLocale());

    expect($translation->options['html'])->not->toContain('<script>');
    expect($translation->options['html'])->not->toContain('alert("XSS")');
    expect($translation->options['html'])->toContain('Safe content');
    expect($translation->options['html'])->toContain('More safe content');
});

it('should sanitize iframe tags from static content', function () {
    $theme = ThemeCustomization::factory()->create(['type' => 'static_content']);

    $this->loginAsAdmin();

    postJson(route('admin.settings.themes.update', $theme->id), [
        app()->getLocale() => [
            'options' => [
                'html' => '<div>Content</div><iframe src="https://malicious.com"></iframe><p>More content</p>',
                'css' => '',
            ],
        ],
        'locale' => app()->getLocale(),
        'type' => 'static_content',
        'name' => fake()->name(),
        'sort_order' => '1',
        'channel_id' => core()->getCurrentChannel()->id,
        'theme_code' => core()->getCurrentChannel()->theme,
        'status' => 'on',
    ])
        ->assertRedirect(route('admin.settings.themes.index'));

    $theme->refresh();
    $translation = $theme->translate(app()->getLocale());

    expect($translation->options['html'])->not->toContain('<iframe');
    expect($translation->options['html'])->not->toContain('malicious.com');
    expect($translation->options['html'])->toContain('Content');
});

it('should sanitize form tags from static content', function () {
    $theme = ThemeCustomization::factory()->create(['type' => 'static_content']);

    $this->loginAsAdmin();

    postJson(route('admin.settings.themes.update', $theme->id), [
        app()->getLocale() => [
            'options' => [
                'html' => '<div>Safe</div><form action="/submit" method="post"><input name="data"></form><p>More</p>',
                'css' => '',
            ],
        ],
        'locale' => app()->getLocale(),
        'type' => 'static_content',
        'name' => fake()->name(),
        'sort_order' => '1',
        'channel_id' => core()->getCurrentChannel()->id,
        'theme_code' => core()->getCurrentChannel()->theme,
        'status' => 'on',
    ])
        ->assertRedirect(route('admin.settings.themes.index'));

    $theme->refresh();
    $translation = $theme->translate(app()->getLocale());

    expect($translation->options['html'])->not->toContain('<form');
    expect($translation->options['html'])->not->toContain('</form>');
});

it('should sanitize event handlers from static content', function () {
    $theme = ThemeCustomization::factory()->create(['type' => 'static_content']);

    $this->loginAsAdmin();

    postJson(route('admin.settings.themes.update', $theme->id), [
        app()->getLocale() => [
            'options' => [
                'html' => '<div onclick="alert(\'XSS\')">Click me</div><img src="x" onerror="alert(\'XSS\')">',
                'css' => '',
            ],
        ],
        'locale' => app()->getLocale(),
        'type' => 'static_content',
        'name' => fake()->name(),
        'sort_order' => '1',
        'channel_id' => core()->getCurrentChannel()->id,
        'theme_code' => core()->getCurrentChannel()->theme,
        'status' => 'on',
    ])
        ->assertRedirect(route('admin.settings.themes.index'));

    $theme->refresh();
    $translation = $theme->translate(app()->getLocale());

    expect($translation->options['html'])->not->toContain('onclick');
    expect($translation->options['html'])->not->toContain('onerror');
    expect($translation->options['html'])->toContain('Click me');
});

it('should preserve safe HTML in static content', function () {
    $theme = ThemeCustomization::factory()->create(['type' => 'static_content']);

    $safeHtml = '<div class="container"><h1>Title</h1><p>Paragraph with <strong>bold</strong> and <em>italic</em> text.</p><ul><li>Item 1</li></ul></div>';

    $this->loginAsAdmin();

    postJson(route('admin.settings.themes.update', $theme->id), [
        app()->getLocale() => [
            'options' => [
                'html' => $safeHtml,
                'css' => 'body { color: blue; }',
            ],
        ],
        'locale' => app()->getLocale(),
        'type' => 'static_content',
        'name' => fake()->name(),
        'sort_order' => '1',
        'channel_id' => core()->getCurrentChannel()->id,
        'theme_code' => core()->getCurrentChannel()->theme,
        'status' => 'on',
    ])
        ->assertRedirect(route('admin.settings.themes.index'));

    $theme->refresh();
    $translation = $theme->translate(app()->getLocale());

    expect($translation->options['html'])->toContain('<div');
    expect($translation->options['html'])->toContain('<h1>');
    expect($translation->options['html'])->toContain('<strong>');
    expect($translation->options['html'])->toContain('<em>');
});

it('should skip sanitization for non-static content theme types', function () {
    $theme = ThemeCustomization::factory()->create(['type' => 'product_carousel']);

    $this->loginAsAdmin();

    postJson(route('admin.settings.themes.update', $theme->id), [
        app()->getLocale() => [
            'options' => [
                'title' => 'Test Title',
                'filters' => ['sort' => 'name-desc', 'limit' => '12', 'new' => '1'],
            ],
        ],
        'locale' => app()->getLocale(),
        'type' => 'product_carousel',
        'name' => $name = fake()->name(),
        'sort_order' => '1',
        'channel_id' => core()->getCurrentChannel()->id,
        'theme_code' => core()->getCurrentChannel()->theme,
        'status' => 'on',
    ])
        ->assertRedirect(route('admin.settings.themes.index'));

    $this->assertDatabaseHas('theme_customizations', [
        'id' => $theme->id,
        'type' => 'product_carousel',
        'name' => $name,
    ]);
});
