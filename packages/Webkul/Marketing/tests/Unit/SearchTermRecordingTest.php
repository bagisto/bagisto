<?php

use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Support\Facades\DB;
use Webkul\Marketing\Models\SearchTerm;
use Webkul\Marketing\Repositories\SearchTermRepository;

/**
 * Record one search of a term in the current channel, in English unless told otherwise.
 */
function recordSearchOf(string $term, int $results = 1, ?int $channelId = null, string $locale = 'en'): void
{
    app(SearchTermRepository::class)->recordSearch([
        'term' => $term,
        'results' => $results,
        'channel_id' => $channelId ?? core()->getCurrentChannel()->id,
        'locale' => $locale,
    ]);
}

/**
 * The rows stored for a term, whatever channel or locale they belong to.
 */
function storedTerms(string $term)
{
    return SearchTerm::query()->where('term', $term)->get();
}

// ============================================================================
// Recording A Search
// ============================================================================

it('should store a term the first time it is searched for', function () {
    recordSearchOf('alpaca jumper', results: 4);

    $rows = storedTerms('alpaca jumper');

    expect($rows)->toHaveCount(1)
        ->and((int) $rows->first()->uses)->toBe(1)
        ->and((int) $rows->first()->results)->toBe(4);
});

it('should count a repeated search against the one row rather than storing it again', function () {
    recordSearchOf('alpaca jumper', results: 4);
    recordSearchOf('alpaca jumper', results: 4);
    recordSearchOf('alpaca jumper', results: 4);

    $rows = storedTerms('alpaca jumper');

    expect($rows)->toHaveCount(1)
        ->and((int) $rows->first()->uses)->toBe(3);
});

it('should refresh the result count to what the latest search found', function () {
    recordSearchOf('alpaca jumper', results: 4);
    recordSearchOf('alpaca jumper', results: 9);

    expect((int) storedTerms('alpaca jumper')->first()->results)->toBe(9);
});

it('should keep the same term in another locale as its own row', function () {
    recordSearchOf('alpaca jumper', locale: 'en');
    recordSearchOf('alpaca jumper', locale: 'de');

    expect(storedTerms('alpaca jumper'))->toHaveCount(2)
        ->and(storedTerms('alpaca jumper')->pluck('locale')->sort()->values()->all())->toBe(['de', 'en']);
});

// ============================================================================
// Concurrency
// ============================================================================

it('should let the database refuse a second row for the same term, channel and locale', function () {
    recordSearchOf('alpaca jumper');

    $duplicate = fn () => DB::table('search_terms')->insert([
        'term' => 'alpaca jumper',
        'channel_id' => core()->getCurrentChannel()->id,
        'locale' => 'en',
        'uses' => 1,
        'results' => 1,
        'display_in_suggested_terms' => 0,
    ]);

    expect($duplicate)->toThrow(UniqueConstraintViolationException::class);
});

it('should count the search against a row this request never created', function () {
    DB::table('search_terms')->insert([
        'term' => 'alpaca jumper',
        'channel_id' => core()->getCurrentChannel()->id,
        'locale' => 'en',
        'uses' => 1,
        'results' => 2,
        'display_in_suggested_terms' => 0,
    ]);

    recordSearchOf('alpaca jumper', results: 5);

    $rows = storedTerms('alpaca jumper');

    expect($rows)->toHaveCount(1)
        ->and((int) $rows->first()->uses)->toBe(2)
        ->and((int) $rows->first()->results)->toBe(5);
});

it('should record a term the database has never seen in one statement', function () {
    $channelId = core()->getCurrentChannel()->id;

    $statements = [];

    DB::listen(function ($query) use (&$statements) {
        $statements[] = $query->sql;
    });

    recordSearchOf('alpaca jumper', results: 4, channelId: $channelId);

    expect($statements)->toHaveCount(1)
        ->and((int) storedTerms('alpaca jumper')->first()->uses)->toBe(1);
});
