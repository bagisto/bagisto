<?php

use Illuminate\Http\UploadedFile;
use Webkul\Theme\Models\ThemeCustomization;

use function Pest\Laravel\deleteJson;
use function Pest\Laravel\get;
use function Pest\Laravel\postJson;

afterEach(function () {
    ThemeCustomization::query()->whereNotBetween('id', [1, 12])->delete();
});

it('should returns the theme index page', function () {
    // Act and Assert
    $this->loginAsAdmin();

    get(route('admin.settings.themes.index'))
        ->assertOk()
        ->assertSeeText(trans('admin::app.settings.themes.index.title'))
        ->assertSeeText(trans('admin::app.settings.themes.index.create-btn'));
});

it('should store the newly created theme', function () {
    // Arrange
    $lastThemeId = ThemeCustomization::factory()->create()->id + 1;

    $types = ['product_carousel', 'category_carousel', 'image_carousel', 'footer_links', 'services_content'];

    // Act and Assert
    $this->loginAsAdmin();

    postJson(route('admin.settings.themes.store'), [
        'type'       => $type = fake()->randomElement($types),
        'name'       => $name = fake()->name(),
        'sort_order' => $lastThemeId,
        'channel_id' => $channelId = core()->getCurrentChannel()->id,
    ])
        ->assertOk()
        ->assertJsonPath('redirect_url', route('admin.settings.themes.edit', $lastThemeId));

    $this->assertDatabaseHas('theme_customizations', [
        'id'         => $lastThemeId,
        'type'       => $type,
        'name'       => $name,
        'channel_id' => $channelId,
    ]);
});

it('should update the theme customizations', function () {
    $theme = ThemeCustomization::factory()->create();

    $data = [];

    switch ($theme->type) {
        case ThemeCustomization::PRODUCT_CAROUSEL:
            $data[app()->getLocale()] = [
                'options' => [
                    'title'   => fake()->title(),
                    'filters' => [
                        'sort'  => 'name-desc',
                        'limit' => '12',
                        'new'   => '1',
                    ],
                ],
            ];

            break;
        case ThemeCustomization::CATEGORY_CAROUSEL:
            $data[app()->getLocale()] = [
                'options' => [
                    'title'   => fake()->title(),
                    'filters' => [
                        'sort'      => 'desc',
                        'limit'     => '10',
                        'parent_id' => '1',
                    ],
                ],
            ];

            break;
        case ThemeCustomization::IMAGE_CAROUSEL:
            $data[app()->getLocale()] = [
                'options' => [
                    [
                        'title' => fake()->title(),
                        'link'  => fake()->url(),
                        'image' => UploadedFile::fake()->create(fake()->word() . '.png'),
                    ],
                ],
            ];

            break;
        case ThemeCustomization::FOOTER_LINKS:

            $data[app()->getLocale()] = [
                'options' => [
                    'column_1' => [
                        [
                            'url'        => fake()->url(),
                            'title'      => fake()->title(),
                            'sort_order' => '1',
                        ],
                    ],
                ],
            ];

            break;

        case ThemeCustomization::SERVICES_CONTENT:
            $data[app()->getLocale()] = [
                'options' => [
                    [
                        'title'        => fake()->title(),
                        'description'  => fake()->paragraph(),
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
    $data['status'] = 'on';

    // Act and Assert
    $this->loginAsAdmin();

    postJson(route('admin.settings.themes.update', $theme->id), $data)
        ->assertRedirect(route('admin.settings.themes.index'))
        ->isRedirection();

    $this->assertDatabaseHas('theme_customizations', [
        'id'   => $theme->id,
        'type' => $theme->type,
        'name' => $name,
    ]);
});

it('should delete the theme', function () {
    // Arrange
    $theme = ThemeCustomization::factory()->create();

    // Act and Assert
    $this->loginAsAdmin();

    deleteJson(route('admin.settings.themes.delete', $theme->id))
        ->assertOk()
        ->assertJsonPath('message', trans('admin::app.settings.themes.delete-success'));

    $this->assertDatabaseMissing('theme_customizations', [
        'id' => $theme->id,
    ]);
});
