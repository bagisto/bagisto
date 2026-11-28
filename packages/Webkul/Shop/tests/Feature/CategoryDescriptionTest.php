<?php

use Webkul\Category\Models\Category;

use function Pest\Laravel\get;

/**
 * The description markup the category under test carries, which the page meta tags never repeat.
 */
function categoryDescriptionMarkup(): string
{
    return '<p>Long form copy about the category.</p>';
}

/**
 * Create a category under the current channel root that carries the description under test.
 */
function categoryWithDescription(?string $displayMode = 'products_and_description'): Category
{
    return Category::factory()
        ->hasTranslations(['description' => categoryDescriptionMarkup()])
        ->create([
            'parent_id' => core()->getCurrentChannel()->root_category_id,
            'display_mode' => $displayMode,
        ]);
}

/**
 * Render the storefront page of the given category.
 */
function categoryPage(Category $category): string
{
    return get(route('shop.product_or_category.index', $category->translate('en')->slug))
        ->assertOk()
        ->getContent();
}

// ============================================================================
// Description Position
// ============================================================================

it('should show the description above the products by default', function () {
    $content = categoryPage(categoryWithDescription());

    expect($content)->toContain(categoryDescriptionMarkup())
        ->and(strpos($content, categoryDescriptionMarkup()))->toBeLessThan(strpos($content, '<v-category>'));
});

it('should show the description above the products when the position is top', function () {
    $this->setConfig('catalog.products.category_view_page.description_position', 'top');

    $content = categoryPage(categoryWithDescription());

    expect($content)->toContain(categoryDescriptionMarkup())
        ->and(strpos($content, categoryDescriptionMarkup()))->toBeLessThan(strpos($content, '<v-category>'));
});

it('should show the description below the products when the position is bottom', function () {
    $this->setConfig('catalog.products.category_view_page.description_position', 'bottom');

    $content = categoryPage(categoryWithDescription());

    expect(substr_count($content, categoryDescriptionMarkup()))->toBe(1)
        ->and(strpos($content, categoryDescriptionMarkup()))->toBeGreaterThan(strpos($content, '<v-category>'));
});

it('should show the description of a description only category when the position is bottom', function () {
    $this->setConfig('catalog.products.category_view_page.description_position', 'bottom');

    $content = categoryPage(categoryWithDescription('description_only'));

    expect($content)->toContain(categoryDescriptionMarkup())
        ->not->toContain('<v-category>');
});

it('should not show the description of a products only category', function (string $position) {
    $this->setConfig('catalog.products.category_view_page.description_position', $position);

    $content = categoryPage(categoryWithDescription('products_only'));

    expect($content)->not->toContain(categoryDescriptionMarkup());
})->with(['top', 'bottom']);

// ============================================================================
// Collapsible Description
// ============================================================================

it('should show the whole description without the collapsible panel by default', function () {
    $content = categoryPage(categoryWithDescription());

    expect($content)->toContain(categoryDescriptionMarkup())
        ->not->toContain('<v-category-description>')
        ->not->toContain('v-category-description-template');
});

it('should wrap the description in the collapsible panel when it is enabled', function (string $position) {
    $this->setConfig([
        'catalog.products.category_view_page.description_position' => $position,
        'catalog.products.category_view_page.collapsible_description' => '1',
    ]);

    $content = categoryPage(categoryWithDescription());

    expect($content)->toContain(categoryDescriptionMarkup(), 'v-category-description-template')
        ->toContain(trans('shop::app.categories.view.read-more'))
        ->and(strpos($content, '<v-category-description>'))->toBeLessThan(strpos($content, categoryDescriptionMarkup()))
        ->and(strpos($content, categoryDescriptionMarkup()))->toBeLessThan(strpos($content, '</v-category-description>'));
})->with(['top', 'bottom']);

it('should not render the collapsible panel for a category without a description', function () {
    $this->setConfig('catalog.products.category_view_page.collapsible_description', '1');

    $category = Category::factory()
        ->hasTranslations(['description' => ''])
        ->create(['parent_id' => core()->getCurrentChannel()->root_category_id]);

    expect(categoryPage($category))->not->toContain('<v-category-description>');
});
