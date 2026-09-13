<?php

use Webkul\Category\Models\Category;
use Webkul\Category\Models\CategoryTranslation;
use Webkul\Core\Models\Locale;
use Webkul\FPC\Listeners\Category as CategoryListener;

/**
 * A category with a slug in the given locale.
 */
function categoryWithSlug(string $slug, string $locale = 'en', ?Category $category = null): Category
{
    $category ??= Category::factory()->create();

    CategoryTranslation::factory()->create([
        'category_id' => $category->id,
        'locale' => $locale,
        'locale_id' => Locale::query()->where('code', $locale)->value('id'),
        'slug' => $slug,
    ]);

    return $category->load('translations');
}

beforeEach(function () {
    $this->useIsolatedPageCache();

    $this->listener = app(CategoryListener::class);
});

it('drops the home page when a category is created', function () {
    $category = categoryWithSlug('summer-sale');

    $home = $this->cachePage('/');

    $this->listener->afterCreate($category);

    $this->assertPageNotCached($home, 'A new category is listed on the home page, which kept its old copy.');
});

it('drops both the category page and the home page when a category is updated', function () {
    $category = categoryWithSlug('summer-sale');

    $home = $this->cachePage('/');

    $page = $this->cachePage('/summer-sale');

    $this->listener->afterUpdate($category);

    $this->assertPageNotCached($page);

    $this->assertPageNotCached($home, 'The home page carousel kept the name and image the category had before.');
});

it('drops a category page cached under a second locale', function () {
    $secondScope = $this->addSecondScope();

    $category = categoryWithSlug('summer-sale');

    categoryWithSlug('sommer-schluss', $this->secondLocale->code, $category);

    $translated = $this->cachePage('/sommer-schluss', $secondScope);

    $this->listener->afterUpdate($category);

    $this->assertPageNotCached($translated, 'Only the scope the admin was browsing was forgotten.');
});

it('drops the category pages before the category is deleted', function () {
    $category = categoryWithSlug('summer-sale');

    $home = $this->cachePage('/');

    $page = $this->cachePage('/summer-sale');

    $this->listener->beforeDelete($category->id);

    $this->assertPageNotCached($page);

    $this->assertPageNotCached($home);
});

it('does nothing when the category being deleted is already gone', function () {
    $home = $this->cachePage('/');

    $this->listener->beforeDelete(0);

    $this->assertPageCached($home);
});
