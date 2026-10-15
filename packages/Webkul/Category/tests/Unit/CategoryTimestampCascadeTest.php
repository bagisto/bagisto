<?php

use Illuminate\Support\Facades\DB;
use Webkul\Category\Models\Category;

/**
 * Build a chain of categories of the given depth under the current channel's root, outermost last.
 */
function categoryChain(int $depth): array
{
    $parentId = core()->getCurrentChannel()->root_category_id;

    $chain = [];

    for ($level = 0; $level < $depth; $level++) {
        $chain[] = $parentId = Category::factory()->create(['parent_id' => $parentId])->id;
    }

    return $chain;
}

/**
 * Date the given categories back, so a timestamp that moves is visible within the same second.
 */
function datedBack(array $categoryIds): string
{
    $past = now()->subDay()->startOfSecond();

    Category::query()->whereIn('id', $categoryIds)->toBase()->update(['updated_at' => $past]);

    return (string) $past;
}

/**
 * The `updated_at` of each given category, keyed by id.
 */
function updatedAtOf(array $categoryIds): array
{
    return Category::query()
        ->whereIn('id', $categoryIds)
        ->pluck('updated_at', 'id')
        ->map(fn ($value) => (string) $value)
        ->all();
}

/**
 * Save a category and return the statements that ran, so the cost of the cascade is measurable.
 */
function statementsWhileSaving(Category $category): array
{
    $statements = [];

    DB::listen(function ($query) use (&$statements) {
        $statements[] = $query->sql;
    });

    $category->save();

    return $statements;
}

// ============================================================================
// Descendant Timestamps
// ============================================================================

it('should move the last-modified date of every descendant when an ancestor is saved', function () {
    [$parentId, $childId, $grandChildId] = categoryChain(3);

    $past = datedBack([$childId, $grandChildId]);

    Category::query()->findOrFail($parentId)->save();

    expect(updatedAtOf([$childId, $grandChildId]))
        ->each->not->toBe($past);
});

it('should reach a descendant several levels down, not only the direct children', function () {
    $chain = categoryChain(4);

    $descendants = array_slice($chain, 1);

    $past = datedBack($descendants);

    Category::query()->findOrFail($chain[0])->save();

    foreach (updatedAtOf($descendants) as $id => $updatedAt) {
        expect($updatedAt)->not->toBe($past, "category {$id} was left behind");
    }
});

it('should leave a sibling subtree alone', function () {
    $branch = categoryChain(2);
    $sibling = categoryChain(2);

    $past = datedBack(array_merge($branch, $sibling));

    Category::query()->findOrFail($branch[0])->save();

    expect(updatedAtOf([$branch[1]])[$branch[1]])->not->toBe($past)
        ->and(updatedAtOf($sibling))->each->toBe($past);
});

it('should leave every other category alone when a leaf is saved', function () {
    $branch = categoryChain(2);

    $past = datedBack($branch);

    Category::query()->findOrFail($branch[1])->save();

    expect(updatedAtOf([$branch[0]])[$branch[0]])->toBe($past);
});

// ============================================================================
// Cost Of The Cascade
// ============================================================================

it('should touch the whole subtree in one statement rather than one per descendant', function () {
    $shallow = categoryChain(2);
    $deep = categoryChain(6);

    datedBack(array_merge($shallow, $deep));

    $countUpdates = fn (array $statements) => count(array_filter(
        $statements,
        fn ($sql) => stripos($sql, 'update') === 0
    ));

    $shallowUpdates = $countUpdates(statementsWhileSaving(Category::query()->findOrFail($shallow[0])));
    $deepUpdates = $countUpdates(statementsWhileSaving(Category::query()->findOrFail($deep[0])));

    expect($deepUpdates)->toBe($shallowUpdates)
        ->and($deepUpdates)->toBeLessThanOrEqual(2);
});
