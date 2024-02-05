<?php

use Webkul\CMS\Models\Page;
use Webkul\CMS\Models\PageTranslation;

use function Pest\Laravel\deleteJson;
use function Pest\Laravel\get;
use function Pest\Laravel\getJson;
use function Pest\Laravel\postJson;
use function Pest\Laravel\putJson;

it('should returns the cms page', function () {
    // Act and Assert
    $this->loginAsAdmin();

    get(route('admin.cms.index'))
        ->assertOk()
        ->assertSeeText(trans('admin::app.cms.index.title'))
        ->assertSeeText(trans('admin::app.cms.index.create-btn'));
});

it('should returns the listing cms', function () {
    // Act and Assert
    $this->loginAsAdmin();

    getJson(route('admin.cms.index'), [
        'X-Requested-With' => 'XMLHttpRequest',
    ])
        ->assertOk()
        ->assertJsonPath('records.0.id', 11)
        ->assertJsonPath('records.0.page_title', 'Privacy Policy')
        ->assertJsonPath('records.0.url_key', 'privacy-policy')
        ->assertJsonPath('meta.total', 11);
});

it('should create the new cms page', function () {
    // Act and Assert
    $this->loginAsAdmin();

    get(route('admin.cms.create'))
        ->assertOk()
        ->assertSeeText(trans('admin::app.cms.create.title'))
        ->assertSeeText(trans('admin::app.account.edit.back-btn'));
});

it('should fail the validation with errors when certain inputs are not provided when store in cms page', function () {
    // Act and Assert
    $this->loginAsAdmin();

    postJson(route('admin.cms.store'))
        ->assertJsonValidationErrorFor('url_key')
        ->assertJsonValidationErrorFor('page_title')
        ->assertJsonValidationErrorFor('channels')
        ->assertJsonValidationErrorFor('html_content')
        ->assertUnprocessable();
});

it('should store newly created cms pages', function () {
    // Act and Assert
    $this->loginAsAdmin();

    postJson(route('admin.cms.store'), [
        'url_key'      => $slug = fake()->slug(),
        'page_title'   => $pageTitle = fake()->title(),
        'html_content' => $htmlContent = substr(fake()->paragraph(), 0, 50),
        'channels'     => [
            'value' => 1,
        ],
    ])
        ->assertRedirect(route('admin.cms.index'))
        ->isRedirection();

    $this->assertModelWise([
        PageTranslation::class => [
            [
                'url_key'      => $slug,
                'page_title'   => $pageTitle,
                'html_content' => $htmlContent,
            ],
        ],
    ]);
});

it('should show the edit cms page', function () {
    // Arrange
    $cms = Page::factory()->hasTranslations()->create();

    // Act and Assert
    $this->loginAsAdmin();

    get(route('admin.cms.edit', $cms->id))
        ->assertOk()
        ->assertSeeText(trans('admin::app.cms.edit.title'))
        ->assertSeeText(trans('admin::app.cms.edit.back-btn'));
});

it('should fail the validation with errors when certain inputs are not provided when update in cms page', function () {
    // Arrange
    $cms = Page::factory()->hasTranslations()->create();

    $localeCode = core()->getRequestedLocaleCode();

    // Act and Assert
    $this->loginAsAdmin();

    putJson(route('admin.cms.update', $cms->id))
        ->assertJsonValidationErrorFor($localeCode.'.url_key')
        ->assertJsonValidationErrorFor($localeCode.'.page_title')
        ->assertJsonValidationErrorFor($localeCode.'.html_content')
        ->assertJsonValidationErrorFor('channels')
        ->assertUnprocessable();
});

it('should update the cms page', function () {
    // Arrange
    $cms = Page::factory()->hasTranslations()->create();

    $localeCode = core()->getCurrentLocale()->code;

    // Act and Assert
    $this->loginAsAdmin();

    putJson(route('admin.cms.update', $cms->id), [
        $localeCode => [
            'url_key'      => $cms->url_key,
            'page_title'   => $pageTitle = fake()->word(),
            'html_content' => $htmlContent = substr(fake()->paragraph(), 0, 50),
        ],

        'locale' => $localeCode,

        'channels' => [
            1,
        ],
    ])
        ->assertRedirect(route('admin.cms.index'))
        ->isRedirection();

    $this->assertModelWise([
        PageTranslation::class => [
            [
                'url_key'      => $cms->url_key,
                'page_title'   => $pageTitle,
                'html_content' => $htmlContent,
            ],
        ],
    ]);
});

it('should delete the cms page', function () {
    // Arrange
    $cms = Page::factory()->hasTranslations()->create();

    // Act and Assert
    $this->loginAsAdmin();

    deleteJson(route('admin.cms.delete', $cms->id))
        ->assertOk()
        ->assertSeeText(trans('admin::app.cms.delete-success'));

    $this->assertDatabaseMissing('cms_pages', [
        'id' => $cms->id,
    ]);
});

it('should mass delete cms pages', function () {
    // Arrange
    $cmsPages = Page::factory()->count(2)->hasTranslations()->create();

    // Act and Assert
    $this->loginAsAdmin();

    postJson(route('admin.cms.mass_delete'), [
        'indices' => $cmsPages->pluck('id')->toArray(),
    ])
        ->assertOk()
        ->assertSeeText(trans('admin::app.cms.index.datagrid.mass-delete-success'));

    foreach ($cmsPages as $page) {
        $this->assertDatabaseMissing('cms_pages', [
            'id' => $page->id,
        ]);
    }
});
