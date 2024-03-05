<?php

use Illuminate\Http\UploadedFile;
use Webkul\Theme\Models\ThemeCustomization;

use function Pest\Laravel\deleteJson;
use function Pest\Laravel\get;
use function Pest\Laravel\postJson;

it('should returns the theme index page', function () {
    // Act and Assert
    $this->loginAsAdmin();

    get(route('admin.settings.themes.index'))
        ->assertOk()
        ->assertSeeText(trans('admin::app.settings.themes.index.title'))
        ->assertSeeText(trans('admin::app.settings.themes.index.create-btn'));
});

it('should fail the validation with errors when certain field not provided when store the theme', function () {
    // Act and Assert
    $this->loginAsAdmin();

    postJson(route('admin.settings.themes.store'))
        ->assertJsonValidationErrorFor('name')
        ->assertJsonValidationErrorFor('sort_order')
        ->assertJsonValidationErrorFor('type')
        ->assertJsonValidationErrorFor('channel_id')
        ->assertUnprocessable();
});

it('should fail the validation with errors when correct type not provided when store the theme', function () {
    // Act and Assert
    $this->loginAsAdmin();

    postJson(route('admin.settings.themes.store'), [
        'type' => fake()->word(),
    ])
        ->assertJsonValidationErrorFor('name')
        ->assertJsonValidationErrorFor('sort_order')
        ->assertJsonValidationErrorFor('type')
        ->assertJsonValidationErrorFor('channel_id')
        ->assertUnprocessable();
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

    $this->assertModelWise([
        ThemeCustomization::class => [
            [
                'id'         => $lastThemeId,
                'type'       => $type,
                'name'       => $name,
                'channel_id' => $channelId,
            ],
        ],
    ]);
});

it('should fail the validation with errors when correct type not provided when update the theme', function () {
    // Arrange
    $theme = ThemeCustomization::factory()->create();

    // Act and Assert
    $this->loginAsAdmin();

    postJson(route('admin.settings.themes.update', $theme->id))
        ->assertJsonValidationErrorFor('name')
        ->assertJsonValidationErrorFor('sort_order')
        ->assertJsonValidationErrorFor('type')
        ->assertJsonValidationErrorFor('channel_id')
        ->assertUnprocessable();
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
                        'image' => UploadedFile::fake()->image(fake()->word().'.png', 640, 480, 'png'),
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

    $this->assertModelWise([
        ThemeCustomization::class => [
            [
                'id'   => $theme->id,
                'type' => $theme->type,
                'name' => $name,
            ],
        ],
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
