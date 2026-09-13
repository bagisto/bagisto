<?php

use Webkul\Attribute\Models\Attribute;
use Webkul\Attribute\Models\AttributeOption;
use Webkul\Category\Models\Category;
use Webkul\Core\Helpers\CacheGeneration;
use Webkul\Core\Models\Channel;
use Webkul\Core\Repositories\ChannelRepository;

/**
 * Create a select attribute, filterable or not, with an option per given sort order.
 */
function selectAttribute(bool $filterable, array $optionSortOrders = []): Attribute
{
    $attribute = Attribute::factory()->create([
        'type' => 'select',
        'is_filterable' => $filterable,
    ]);

    foreach ($optionSortOrders as $sortOrder) {
        AttributeOption::factory()->create([
            'attribute_id' => $attribute->id,
            'sort_order' => $sortOrder,
        ]);
    }

    return $attribute;
}

/**
 * Create a category under the given parent, the current channel's root when none is given.
 */
function categoryUnder(?int $parentId = null): Category
{
    return Category::factory()->create([
        'parent_id' => $parentId ?? core()->getCurrentChannel()->root_category_id,
    ]);
}

// ============================================================================
// Filterable Attributes
// ============================================================================

it('should list only the filterable attributes attached to the category', function () {
    $category = categoryUnder();

    $filterableIds = [selectAttribute(true)->id, selectAttribute(true)->id];

    $category->filterableAttributes()->attach([...$filterableIds, selectAttribute(false)->id]);

    expect($category->filterableAttributes->pluck('id')->all())->toEqualCanonicalizing($filterableIds);
});

it('should list the options of a filterable attribute in their sort order', function () {
    $category = categoryUnder();

    $category->filterableAttributes()->attach(selectAttribute(true, [3, 1, 2])->id);

    expect($category->filterableAttributes->first()->options->pluck('sort_order')->all())->toBe([1, 2, 3]);
});

it('should list no filterable attributes for a category none is attached to', function () {
    expect(categoryUnder()->filterableAttributes)->toBeEmpty();
});

// ============================================================================
// Channel Availability
// ============================================================================

it('should be available in the channel whose root category it sits under', function () {
    $category = categoryUnder();

    expect($category->isAvailableInChannel())->toBeTrue()
        ->and($category->isAvailableInChannel(core()->getCurrentChannel()->id))->toBeTrue();
});

it('should be available in a channel as that channel root category', function () {
    $channel = core()->getCurrentChannel();

    expect(Category::query()->findOrFail($channel->root_category_id)->isAvailableInChannel($channel->id))->toBeTrue();
});

it('should be available only in the channels whose tree it sits in', function () {
    $otherRoot = Category::factory()->create(['parent_id' => null]);

    $category = categoryUnder($otherRoot->id);

    $defaultChannel = core()->getDefaultChannel();

    $otherChannel = Channel::factory()->create([
        'root_category_id' => $otherRoot->id,
        'default_locale_id' => $defaultChannel->default_locale_id,
        'base_currency_id' => $defaultChannel->base_currency_id,
    ]);

    CacheGeneration::bump(ChannelRepository::class);

    expect($category->isAvailableInChannel($otherChannel->id))->toBeTrue()
        ->and($category->isAvailableInChannel($defaultChannel->id))->toBeFalse()
        ->and($otherRoot->isAvailableInChannel($defaultChannel->id))->toBeFalse();
});

it('should not be available in a channel that does not exist', function () {
    expect(categoryUnder()->isAvailableInChannel(Channel::query()->max('id') + 1000))->toBeFalse();
});
