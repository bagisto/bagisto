<?php

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Webkul\Attribute\Models\Attribute;
use Webkul\Category\Models\Category;
use Webkul\Faker\Helpers\Category as CategoryFaker;

use function Pest\Laravel\get;
use function Pest\Laravel\putJson;

function makeCategoryWithLogo(): Category
{
    $category = (new CategoryFaker)->factory()->create();

    $category->logo_path = 'category/'.$category->id.'/kd83nfhs.webp';

    $category->banner_path = 'category/'.$category->id.'/pw81mcka.webp';

    $category->save();

    Storage::put($category->logo_path, 'logo-contents');

    Storage::put($category->banner_path, 'banner-contents');

    return $category;
}

function categoryUpdatePayload(Category $category, array $extra = []): array
{
    return array_merge([
        'en' => [
            'name' => fake()->name(),
            'description' => substr(fake()->paragraph(), 0, 50),
            'slug' => $category->slug,
        ],
        'locale' => config('app.locale'),
        'attributes' => Attribute::where('is_filterable', 1)->pluck('id')->toArray(),
        'position' => 1,
    ], $extra);
}

it('should render the seo drawer on the category edit page', function () {
    $category = makeCategoryWithLogo();

    $this->loginAsAdmin();

    get(route('admin.catalog.categories.edit', $category->id))
        ->assertOk()
        ->assertSee('logo_meta')
        ->assertSee('banner_meta')
        ->assertSee(trans('admin::app.components.media.images.seo.alt-text'));
});

it('should save the alt text of the category logo and banner', function () {
    Storage::fake();

    $category = makeCategoryWithLogo();

    $this->loginAsAdmin();

    putJson(route('admin.catalog.categories.update', $category->id), categoryUpdatePayload($category, [
        'logo_path' => ['logo_path' => ''],
        'logo_meta' => ['logo_path' => ['alt_text' => 'Winter collection logo']],
        'banner_path' => ['banner_path' => ''],
        'banner_meta' => ['banner_path' => ['alt_text' => 'Winter collection banner']],
    ]))->assertRedirect(route('admin.catalog.categories.index'));

    $category = $category->fresh();

    expect($category->logo_alt)->toBe('Winter collection logo');

    expect($category->banner_alt)->toBe('Winter collection banner');
});

it('should rename the category logo file', function () {
    Storage::fake();

    $category = makeCategoryWithLogo();

    $originalPath = $category->logo_path;

    $this->loginAsAdmin();

    putJson(route('admin.catalog.categories.update', $category->id), categoryUpdatePayload($category, [
        'logo_path' => ['logo_path' => ''],
        'logo_meta' => ['logo_path' => ['file_name' => 'Winter Collection Logo']],
        'banner_path' => ['banner_path' => ''],
    ]))->assertRedirect(route('admin.catalog.categories.index'));

    $expected = 'category/'.$category->id.'/winter-collection-logo.webp';

    expect($category->fresh()->logo_path)->toBe($expected);

    Storage::assertExists($expected);

    Storage::assertMissing($originalPath);
});

it('should name a newly uploaded category logo after the requested file name', function () {
    Storage::fake();

    $category = (new CategoryFaker)->factory()->create();

    $this->loginAsAdmin();

    putJson(route('admin.catalog.categories.update', $category->id), categoryUpdatePayload($category, [
        'logo_path' => ['image_0' => UploadedFile::fake()->image('DSC_0002.png', 20, 20)],
        'logo_meta' => ['image_0' => ['alt_text' => 'Winter collection logo', 'file_name' => 'Winter Collection Logo']],
    ]))->assertRedirect(route('admin.catalog.categories.index'));

    $category = $category->fresh();

    expect($category->logo_path)->toBe('category/'.$category->id.'/winter-collection-logo.webp');

    expect($category->logo_alt)->toBe('Winter collection logo');

    Storage::assertExists($category->logo_path);
});

it('should reject a category alt text longer than the column allows', function () {
    Storage::fake();

    $category = makeCategoryWithLogo();

    $this->loginAsAdmin();

    putJson(route('admin.catalog.categories.update', $category->id), categoryUpdatePayload($category, [
        'logo_path' => ['logo_path' => ''],
        'logo_meta' => ['logo_path' => ['alt_text' => str_repeat('a', 256)]],
    ]))->assertUnprocessable();
});

it('should drop the alt text when the category image is removed', function () {
    Storage::fake();

    $category = makeCategoryWithLogo();

    $category->translateOrNew('en')->logo_alt = 'Winter collection logo';

    $category->save();

    $this->loginAsAdmin();

    putJson(route('admin.catalog.categories.update', $category->id), categoryUpdatePayload($category, [
        'banner_path' => ['banner_path' => ''],
    ]))->assertRedirect(route('admin.catalog.categories.index'));

    $category = $category->fresh();

    expect($category->logo_path)->toBeNull();

    expect($category->logo_alt)->toBeNull();
});
