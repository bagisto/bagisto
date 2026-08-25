<?php

use Illuminate\Http\UploadedFile;
use Webkul\Category\Models\Category;
use Webkul\Core\Models\Channel;
use Webkul\Theme\Models\Section;
use Webkul\Theme\SectionSchema;

use function Pest\Laravel\deleteJson;
use function Pest\Laravel\get;
use function Pest\Laravel\postJson;

it('should returns the section index page', function () {
    // Act and Assert.
    $this->loginAsAdmin();

    get(route('admin.appearance.sections.index', ['code' => core()->getCurrentChannel()->theme]))
        ->assertOk()
        ->assertSeeText(trans('admin::app.components.layouts.sidebar.sections'))
        ->assertSeeText(trans('admin::app.appearance.sections.index.create-btn'));
});

it('should fail the validation with errors when certain field not provided when store the section', function () {
    // Act and Assert.
    $this->loginAsAdmin();

    postJson(route('admin.appearance.sections.store', ['code' => core()->getCurrentChannel()->theme]))
        ->assertJsonValidationErrorFor('name')
        ->assertJsonValidationErrorFor('type')
        ->assertUnprocessable();
});

it('should fail the validation with errors when correct type not provided when store the section', function () {
    // Act and Assert.
    $this->loginAsAdmin();

    postJson(route('admin.appearance.sections.store', ['code' => core()->getCurrentChannel()->theme]), [
        'type' => 'INVALID_TYPE',
    ])
        ->assertJsonValidationErrorFor('name')
        ->assertJsonValidationErrorFor('type')
        ->assertUnprocessable();
});

it('should store the newly created theme', function () {
    // Arrange.
    $lastSectionId = Section::factory()->create()->id + 1;

    // Act and Assert.
    $this->loginAsAdmin();

    postJson(route('admin.appearance.sections.store', ['code' => core()->getCurrentChannel()->theme]), [
        'type' => $type = fake()->randomElement([
            'product_carousel',
            'category_carousel',
            'image_carousel',
            'services_content',
        ]),
        'name' => $name = fake()->name(),
    ])
        ->assertOk()
        ->assertJsonPath('section.id', $lastSectionId)
        ->assertJsonPath('section.name', $name)
        ->assertJsonPath('section.type', $type);

    $channelId = core()->getCurrentChannel()->id;

    $themeCode = core()->getCurrentChannel()->theme;

    $this->assertModelWise([
        Section::class => [
            [
                'id' => $lastSectionId,
                'type' => $type,
                'name' => $name,
                'channel_id' => $channelId,
                'theme_code' => $themeCode,
            ],
        ],
    ]);
});

it('should fail the validation with errors when correct type not provided when update the section', function () {
    // Arrange.
    $section = Section::factory()->create();

    // Act and Assert.
    $this->loginAsAdmin();

    postJson(route('admin.appearance.sections.update', $section->id))
        ->assertJsonValidationErrorFor('name')
        ->assertJsonValidationErrorFor('sort_order')
        ->assertJsonValidationErrorFor('type')
        ->assertJsonValidationErrorFor('channel_id')
        ->assertJsonValidationErrorFor('theme_code')
        ->assertUnprocessable();
});

it('should update the sections', function () {
    $section = Section::factory()->create();

    $data = [];

    switch ($section->type) {
        case Section::PRODUCT_CAROUSEL:
            $data[app()->getLocale()] = [
                'options' => [
                    'title' => fake()->title(),
                    'filters' => [
                        'sort' => 'name-desc',
                        'limit' => '12',
                        'new' => '1',
                    ],
                ],
            ];

            break;

        case Section::CATEGORY_CAROUSEL:
            $data[app()->getLocale()] = [
                'options' => [
                    'title' => fake()->title(),
                    'filters' => [
                        'sort' => 'desc',
                        'limit' => '10',
                        'parent_id' => '1',
                    ],
                ],
            ];

            break;

        case Section::IMAGE_CAROUSEL:
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

        case Section::FOOTER_LINKS:
            $data[app()->getLocale()] = [
                'options' => [
                    'column_1' => [
                        [
                            'url' => fake()->url(),
                            'title' => fake()->title(),
                            'sort_order' => '1',
                        ],
                    ],
                ],
            ];

            break;

        case Section::SERVICES_CONTENT:
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
    $data['type'] = $section->type;
    $data['name'] = $name = fake()->name();
    $data['sort_order'] = '1';
    $data['channel_id'] = core()->getCurrentChannel()->id;
    $data['theme_code'] = core()->getCurrentChannel()->theme;
    $data['status'] = 'on';

    // Act and Assert.
    $this->loginAsAdmin();

    postJson(route('admin.appearance.sections.update', $section->id), $data)
        ->assertRedirect(route('admin.appearance.sections.index', ['code' => core()->getCurrentChannel()->theme]))
        ->isRedirection();

    $this->assertModelWise([
        Section::class => [
            [
                'id' => $section->id,
                'type' => $section->type,
                'name' => $name,
            ],
        ],
    ]);
});

it('should sanitize malicious script tags from static content HTML when updating theme', function () {
    // Arrange.
    $section = Section::factory()->create([
        'type' => 'static_content',
    ]);

    $maliciousHtml = '<div>Safe content</div><script>alert("XSS")</script><p>More safe content</p>';

    $safeCss = 'body { color: red; }';

    $data = [
        app()->getLocale() => [
            'options' => [
                'html' => $maliciousHtml,
                'css' => $safeCss,
            ],
        ],
        'locale' => app()->getLocale(),
        'type' => 'static_content',
        'name' => $name = fake()->name(),
        'sort_order' => '1',
        'channel_id' => core()->getCurrentChannel()->id,
        'theme_code' => core()->getCurrentChannel()->theme,
        'status' => 'on',
    ];

    // Act and Assert.
    $this->loginAsAdmin();

    postJson(route('admin.appearance.sections.update', $section->id), $data)
        ->assertRedirect(route('admin.appearance.sections.index', ['code' => core()->getCurrentChannel()->theme]))
        ->isRedirection();

    $section->refresh();
    $translation = $section->translate(app()->getLocale());

    // Assert that script tag was removed.
    expect($translation->options['html'])->not->toContain('<script>');
    expect($translation->options['html'])->not->toContain('alert("XSS")');
    expect($translation->options['html'])->toContain('Safe content');
    expect($translation->options['html'])->toContain('More safe content');
});

it('should sanitize iframe tags from static content HTML when updating theme', function () {
    // Arrange.
    $section = Section::factory()->create([
        'type' => 'static_content',
    ]);

    $maliciousHtml = '<div>Content</div><iframe src="https://malicious.com"></iframe><p>More content</p>';

    $data = [
        app()->getLocale() => [
            'options' => [
                'html' => $maliciousHtml,
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
    ];

    // Act and Assert.
    $this->loginAsAdmin();

    postJson(route('admin.appearance.sections.update', $section->id), $data)
        ->assertRedirect(route('admin.appearance.sections.index', ['code' => core()->getCurrentChannel()->theme]))
        ->isRedirection();

    $section->refresh();
    $translation = $section->translate(app()->getLocale());

    // Assert that iframe tag was removed.
    expect($translation->options['html'])->not->toContain('<iframe');
    expect($translation->options['html'])->not->toContain('malicious.com');
    expect($translation->options['html'])->toContain('Content');
    expect($translation->options['html'])->toContain('More content');
});

it('should sanitize form tags from static content HTML when updating theme', function () {
    // Arrange.
    $section = Section::factory()->create([
        'type' => 'static_content',
    ]);

    $maliciousHtml = '<div>Safe content</div><form action="/submit" method="post"><input name="data"></form><p>More content</p>';

    $data = [
        app()->getLocale() => [
            'options' => [
                'html' => $maliciousHtml,
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
    ];

    // Act and Assert.
    $this->loginAsAdmin();

    postJson(route('admin.appearance.sections.update', $section->id), $data)
        ->assertRedirect(route('admin.appearance.sections.index', ['code' => core()->getCurrentChannel()->theme]))
        ->isRedirection();

    $section->refresh();
    $translation = $section->translate(app()->getLocale());

    // Assert that form tag was removed.
    expect($translation->options['html'])->not->toContain('<form');
    expect($translation->options['html'])->not->toContain('</form>');
    expect($translation->options['html'])->toContain('Safe content');
    expect($translation->options['html'])->toContain('More content');
});

it('should preserve safe HTML content in static content when updating theme', function () {
    // Arrange.
    $section = Section::factory()->create([
        'type' => 'static_content',
    ]);

    $safeHtml = '<div class="container"><h1>Title</h1><p>Paragraph with <strong>bold</strong> and <em>italic</em> text.</p><ul><li>Item 1</li><li>Item 2</li></ul></div>';

    $safeCss = 'body { color: blue; font-size: 14px; }';

    $data = [
        app()->getLocale() => [
            'options' => [
                'html' => $safeHtml,
                'css' => $safeCss,
            ],
        ],
        'locale' => app()->getLocale(),
        'type' => 'static_content',
        'name' => fake()->name(),
        'sort_order' => '1',
        'channel_id' => core()->getCurrentChannel()->id,
        'theme_code' => core()->getCurrentChannel()->theme,
        'status' => 'on',
    ];

    // Act and Assert.
    $this->loginAsAdmin();

    postJson(route('admin.appearance.sections.update', $section->id), $data)
        ->assertRedirect(route('admin.appearance.sections.index', ['code' => core()->getCurrentChannel()->theme]))
        ->isRedirection();

    $section->refresh();
    $translation = $section->translate(app()->getLocale());

    // Assert that safe HTML elements are preserved.
    expect($translation->options['html'])->toContain('<div');
    expect($translation->options['html'])->toContain('<h1>');
    expect($translation->options['html'])->toContain('<p>');
    expect($translation->options['html'])->toContain('<strong>');
    expect($translation->options['html'])->toContain('<em>');
    expect($translation->options['html'])->toContain('<ul>');
    expect($translation->options['html'])->toContain('<li>');
});

it('should sanitize malicious event handlers from static content HTML when updating theme', function () {
    // Arrange.
    $section = Section::factory()->create([
        'type' => 'static_content',
    ]);

    $maliciousHtml = '<div onclick="alert(\'XSS\')">Click me</div><img src="x" onerror="alert(\'XSS\')">';

    $data = [
        app()->getLocale() => [
            'options' => [
                'html' => $maliciousHtml,
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
    ];

    // Act and Assert.
    $this->loginAsAdmin();

    postJson(route('admin.appearance.sections.update', $section->id), $data)
        ->assertRedirect(route('admin.appearance.sections.index', ['code' => core()->getCurrentChannel()->theme]))
        ->isRedirection();

    $section->refresh();
    $translation = $section->translate(app()->getLocale());

    // Assert that malicious event handlers were removed.
    expect($translation->options['html'])->not->toContain('onclick');
    expect($translation->options['html'])->not->toContain('onerror');
    expect($translation->options['html'])->not->toContain('alert(');
    expect($translation->options['html'])->toContain('Click me');
});

it('should not sanitize HTML for non-static content theme types', function () {
    // Arrange.
    $section = Section::factory()->create([
        'type' => 'product_carousel',
    ]);

    $data = [
        app()->getLocale() => [
            'options' => [
                'title' => 'Test Title',
                'filters' => [
                    'sort' => 'name-desc',
                    'limit' => '12',
                    'new' => '1',
                ],
            ],
        ],
        'locale' => app()->getLocale(),
        'type' => 'product_carousel',
        'name' => $name = fake()->name(),
        'sort_order' => '1',
        'channel_id' => core()->getCurrentChannel()->id,
        'theme_code' => core()->getCurrentChannel()->theme,
        'status' => 'on',
    ];

    // Act and Assert.
    $this->loginAsAdmin();

    postJson(route('admin.appearance.sections.update', $section->id), $data)
        ->assertRedirect(route('admin.appearance.sections.index', ['code' => core()->getCurrentChannel()->theme]))
        ->isRedirection();

    $section->refresh();

    // Assert theme was updated successfully.
    $this->assertModelWise([
        Section::class => [
            [
                'id' => $section->id,
                'type' => 'product_carousel',
                'name' => $name,
            ],
        ],
    ]);
});

it('should delete the section', function () {
    // Arrange.
    $section = Section::factory()->create();

    // Act and Assert.
    $this->loginAsAdmin();

    deleteJson(route('admin.appearance.sections.delete', $section->id))
        ->assertOk()
        ->assertJsonPath('message', trans('admin::app.appearance.sections.delete-success'));

    $this->assertDatabaseMissing('theme_sections', [
        'id' => $section->id,
    ]);

    $this->assertDatabaseMissing('theme_section_translations', [
        'section_id' => $section->id,
    ]);
});

it('should turn a section off and back on again', function () {
    $section = Section::factory()->create(['status' => 1]);

    $this->loginAsAdmin();

    postJson(route('admin.appearance.sections.status', $section->id), ['status' => false])
        ->assertOk()
        ->assertJsonPath('status', false)
        ->assertJsonPath('has_draft', true);

    postJson(route('admin.appearance.sections.publish', $section->theme_code))->assertOk();

    expect((bool) $section->refresh()->status)->toBeFalse();

    postJson(route('admin.appearance.sections.status', $section->id), ['status' => true])
        ->assertOk()
        ->assertJsonPath('status', true);

    postJson(route('admin.appearance.sections.publish', $section->theme_code))->assertOk();

    expect((bool) $section->refresh()->status)->toBeTrue();
});

it('should take the channel and theme of the editor rather than the request when creating', function () {
    // Arrange.
    $channel = core()->getCurrentChannel();

    // Act and Assert.
    $this->loginAsAdmin();

    $response = postJson(route('admin.appearance.sections.store', ['code' => core()->getCurrentChannel()->theme]), [
        'name' => $name = 'Scoped Section',
        'type' => 'product_carousel',
        'channel_id' => 999999,
        'theme_code' => 'not-a-theme',
    ])->assertOk();

    $section = Section::query()->find($response->json('section.id'));

    expect($section->name)->toBe($name)
        ->and($section->channel_id)->toBe($channel->id)
        ->and($section->theme_code)->toBe($channel->theme);
});

it('should scope the listing to the requested channel', function () {
    // Arrange.
    $channel = core()->getCurrentChannel();

    $section = Section::factory()->create(['name' => 'Only On This Channel']);

    // Act and Assert.
    $this->loginAsAdmin();

    get(route('admin.appearance.sections.index', ['code' => $channel->theme, 'channel' => $channel->id]))
        ->assertOk()
        ->assertSee($section->name);

    get(route('admin.appearance.sections.index', ['code' => $channel->theme, 'channel' => 999999]))
        ->assertOk();
});

it('should delete a section', function () {
    // Arrange.
    $section = Section::factory()->create();

    // Act and Assert.
    $this->loginAsAdmin();

    deleteJson(route('admin.appearance.sections.delete', $section->id))
        ->assertOk();

    expect(Section::query()->find($section->id))->toBeNull();
});

it('should no longer expose the mass action endpoints', function () {
    expect(app('router')->getRoutes()->getByName('admin.appearance.sections.mass_update'))->toBeNull()
        ->and(app('router')->getRoutes()->getByName('admin.appearance.sections.mass_delete'))->toBeNull()
        ->and(app('router')->getRoutes()->getByName('admin.appearance.sections.edit'))->toBeNull();
});

it('should place a duplicate directly below its original', function () {
    // Arrange.
    $channel = core()->getCurrentChannel();

    $made = collect(['First', 'Second', 'Third'])->map(fn ($name, $index) => Section::factory()->create([
        'name' => $name,
        'sort_order' => $index + 1,
        'channel_id' => $channel->id,
        'theme_code' => $channel->theme,
    ]));

    // Act and Assert.
    $this->loginAsAdmin();

    postJson(route('admin.appearance.sections.duplicate', $made[1]->id))->assertOk();

    $order = Section::query()
        ->where('channel_id', $channel->id)
        ->where('theme_code', $channel->theme)
        ->whereIn('id', $made->pluck('id')->push(Section::query()->max('id')))
        ->orderBy('sort_order')
        ->pluck('name')
        ->values()
        ->all();

    expect($order)->toBe([
        'First',
        'Second',
        'Second '.trans('admin::app.appearance.sections.index.copy-suffix'),
        'Third',
    ]);
});

it('should label every schema field with something other than the page heading', function () {
    $suspect = [];

    $walk = function (array $fields) use (&$walk, &$suspect) {
        foreach ($fields as $field) {
            $labels = [$field['label'] ?? ''];

            foreach ($field['keys'] ?? [] as $key) {
                $labels[] = $key['label'] ?? '';
            }

            foreach ($labels as $label) {
                if (
                    $label === ''
                    || $label === trans('admin::app.appearance.sections.edit.title')
                    || str_contains($label, 'admin::app')
                ) {
                    $suspect[] = ($field['key'] ?? '?').' => '.$label;
                }
            }

            $walk($field['fields'] ?? []);
        }
    };

    foreach (app(SectionSchema::class)->all() as $fields) {
        $walk($fields);
    }

    expect($suspect)->toBe([]);
});

it('should offer each filter only once so a stored filter cannot be overwritten', function () {
    foreach (app(SectionSchema::class)->all() as $type => $fields) {
        foreach ($fields as $field) {
            if (($field['type'] ?? null) !== SectionSchema::FILTERS) {
                continue;
            }

            $keys = collect($field['keys'])->pluck('value');

            expect($keys->duplicates())->toBeEmpty("{$type} lists a filter key twice")
                ->and($keys)->not->toBeEmpty();
        }
    }
});

it('should offer the same limits to both carousels', function () {
    $schema = app(SectionSchema::class)->all();

    $product = collect($schema['product_carousel'][1]['keys'])->firstWhere('value', 'limit');

    $category = collect($schema['category_carousel'][0]['keys'])->firstWhere('value', 'limit');

    expect($category['options'])->toBe($product['options'])
        ->and($category['options'])->not->toBeEmpty();
});

it('should decide a filter control by its options alone', function () {
    foreach (app(SectionSchema::class)->all() as $type => $fields) {
        foreach ($fields as $field) {
            foreach ($field['keys'] ?? [] as $key) {
                expect($key)->not->toHaveKeys(['searchable', 'input'], "{$type} still flags a control");
            }
        }
    }
});

it('should not ask for a sort order where the rows are dragged instead', function () {
    foreach (app(SectionSchema::class)->all() as $type => $fields) {
        foreach ($fields as $field) {
            $keys = collect($field['fields'] ?? [])->pluck('key');

            expect($keys)->not->toContain('sort_order', "{$type} still asks for a sort order");
        }
    }
});

it('should hand the editor a store url carrying the channel being edited', function () {
    // Arrange.
    $other = Channel::factory()->create(['theme' => core()->getCurrentChannel()->theme]);

    // Act and Assert.
    $this->loginAsAdmin();

    get(route('admin.appearance.sections.index', ['code' => $other->theme, 'channel' => $other->id]))
        ->assertOk()
        ->assertSee(route('admin.appearance.sections.store', [
            'code' => $other->theme,
            'channel' => $other->id,
        ]), false);
});

it('should create a section against the channel the editor is scoped to', function () {
    // Arrange.
    $other = Channel::factory()->create(['theme' => core()->getCurrentChannel()->theme]);

    // Act and Assert.
    $this->loginAsAdmin();

    $response = postJson(route('admin.appearance.sections.store', [
        'code' => $other->theme,
        'channel' => $other->id,
    ]), [
        'name' => 'Belongs To The Other Channel',
        'type' => 'footer_links',
    ])->assertOk();

    $section = Section::query()->find($response->json('section.id'));

    expect($section->channel_id)->toBe($other->id);
});

it('should refuse a second footer links section on the same channel', function () {
    // Arrange.
    $channel = core()->getCurrentChannel();

    Section::factory()->create([
        'type' => 'footer_links',
        'channel_id' => $channel->id,
        'theme_code' => $channel->theme,
    ]);

    // Act and Assert.
    $this->loginAsAdmin();

    postJson(route('admin.appearance.sections.store', [
        'code' => $channel->theme,
        'channel' => $channel->id,
    ]), [
        'name' => 'A Second Footer',
        'type' => 'footer_links',
    ])->assertJsonValidationErrorFor('type');
});

it('should still allow a footer links section on a channel that has none', function () {
    // Arrange.
    $channel = core()->getCurrentChannel();

    Section::factory()->create([
        'type' => 'footer_links',
        'channel_id' => $channel->id,
        'theme_code' => $channel->theme,
    ]);

    $other = Channel::factory()->create(['theme' => $channel->theme]);

    // Act and Assert.
    $this->loginAsAdmin();

    postJson(route('admin.appearance.sections.store', [
        'code' => $other->theme,
        'channel' => $other->id,
    ]), [
        'name' => 'Footer For The Other Channel',
        'type' => 'footer_links',
    ])->assertOk();
});

it('should place a new section above the pinned footer', function () {
    // Arrange.
    $channel = core()->getCurrentChannel();

    $footer = Section::factory()->create([
        'type' => 'footer_links',
        'status' => 1,
        'channel_id' => $channel->id,
        'theme_code' => $channel->theme,
    ]);

    // Act and Assert.
    $this->loginAsAdmin();

    $response = postJson(route('admin.appearance.sections.store', [
        'code' => $channel->theme,
        'channel' => $channel->id,
    ]), [
        'name' => 'Added After The Footer Existed',
        'type' => 'product_carousel',
    ])->assertOk();

    $created = Section::query()->find($response->json('section.id'));

    expect($created->sort_order)->toBeLessThan($footer->refresh()->sort_order);
});

it('should offer categories to search rather than an id to type', function () {
    $schema = app(SectionSchema::class)->all();

    $categoryId = collect($schema['product_carousel'][1]['keys'])->firstWhere('value', 'category_id');

    expect($categoryId['options'])->not->toBeEmpty();

    $labels = collect($categoryId['options'])->pluck('label');

    expect($labels->filter(fn ($label) => $label === ''))->toBeEmpty()
        ->and($labels->duplicates())->toBeEmpty();

});

it('should label every category a filter can hold, so none falls back to a bare id', function () {
    $schema = app(SectionSchema::class)->all();

    $options = collect($schema['category_carousel'][0]['keys'])
        ->firstWhere('value', 'parent_id')['options'];

    $offered = collect($options)->pluck('value')->sort()->values();

    $all = Category::query()->pluck('id')->map(fn ($id) => (string) $id)->sort()->values();

    expect($offered)->toEqual($all);
});

it('should let several categories be picked for the category carousel parent', function () {
    $schema = app(SectionSchema::class)->all();

    $parentId = collect($schema['category_carousel'][0]['keys'])->firstWhere('value', 'parent_id');

    expect($parentId['multiple'])->toBeTrue()
        ->and($parentId['options'])->not->toBeEmpty();
});

it('should offer the same categories to both carousels', function () {
    $schema = app(SectionSchema::class)->all();

    $product = collect($schema['product_carousel'][1]['keys'])->firstWhere('value', 'category_id');

    $category = collect($schema['category_carousel'][0]['keys'])->firstWhere('value', 'parent_id');

    expect($product['options'])->toBe($category['options']);
});

/**
 * A channel with no footer yet, so the single footer rule can be exercised from a known
 * starting point rather than whatever the seeder left behind.
 */
function channelWithoutFooter(): Channel
{
    $channel = core()->getCurrentChannel();

    Section::where('type', Section::FOOTER_LINKS)->get()->each->delete();

    return $channel;
}

it('should refuse a second footer however it is reached', function (string $path) {
    $channel = channelWithoutFooter();

    $theme = $channel->theme ?: 'default';

    $footer = Section::factory()->create([
        'channel_id' => $channel->id,
        'theme_code' => $theme,
        'type' => Section::FOOTER_LINKS,
    ]);

    $other = Section::factory()->create([
        'channel_id' => $channel->id,
        'theme_code' => $theme,
        'type' => Section::STATIC_CONTENT,
    ]);

    $this->loginAsAdmin();

    match ($path) {
        'created' => postJson(route('admin.appearance.sections.store', ['code' => $theme, 'channel' => $channel->id]), [
            'name' => 'Second Footer',
            'type' => Section::FOOTER_LINKS,
        ])->assertJsonValidationErrorFor('type'),

        'copied' => postJson(route('admin.appearance.sections.duplicate', $footer->id))
            ->assertJsonValidationErrorFor('type'),

        'switched' => postJson(route('admin.appearance.sections.update', $other->id), [
            'name' => 'Hijacked',
            'type' => Section::FOOTER_LINKS,
            'sort_order' => 1,
            'channel_id' => $channel->id,
            'theme_code' => $theme,
        ])->assertJsonValidationErrorFor('type'),
    };

    expect(Section::where('type', Section::FOOTER_LINKS)->count())->toBe(1);
})->with(['created', 'copied', 'switched']);

it('should still allow the footer a channel is entitled to', function () {
    $channel = channelWithoutFooter();

    $this->loginAsAdmin();

    postJson(route('admin.appearance.sections.store', ['code' => $channel->theme ?: 'default', 'channel' => $channel->id]), [
        'name' => 'The Footer',
        'type' => Section::FOOTER_LINKS,
    ])->assertOk();

    expect(Section::where('type', Section::FOOTER_LINKS)->count())->toBe(1);
});

it('should let the footer it already has be edited', function () {
    $channel = channelWithoutFooter();

    $theme = $channel->theme ?: 'default';

    $footer = Section::factory()->create([
        'channel_id' => $channel->id,
        'theme_code' => $theme,
        'type' => Section::FOOTER_LINKS,
    ]);

    $this->loginAsAdmin();

    postJson(route('admin.appearance.sections.update', $footer->id), [
        'name' => 'Renamed Footer',
        'type' => Section::FOOTER_LINKS,
        'sort_order' => 9,
        'channel_id' => $channel->id,
        'theme_code' => $theme,
    ])->assertRedirect();

    $this->assertDatabaseHas('theme_sections', ['id' => $footer->id, 'name' => 'Renamed Footer']);
});
