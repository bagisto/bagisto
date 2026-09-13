<?php

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Webkul\Core\Helpers\CacheGeneration;

/**
 * A repository name no other test holds a token for.
 */
function probeRepository(): string
{
    return 'Webkul\\Probe\\Repositories\\'.Str::studly(Str::random(8)).'Repository';
}

/**
 * The key a repository's token is kept under in the cache.
 */
function generationKeyOf(string $repository): string
{
    return 'repository-generation:'.$repository;
}

// ============================================================================
// Tokens
// ============================================================================

it('should hand out a token and keep it for the rest of the request', function () {
    $repository = probeRepository();

    $token = CacheGeneration::get($repository);

    expect($token)->toBeString()->toHaveLength(16)
        ->and(CacheGeneration::get($repository))->toBe($token)
        ->and(Cache::get(generationKeyOf($repository)))->toBe($token);
});

it('should resolve the persisted token again once the request tokens are flushed', function () {
    $repository = probeRepository();

    $token = CacheGeneration::get($repository);

    CacheGeneration::flush();

    expect(CacheGeneration::get($repository))->toBe($token);
});

it('should adopt a token another request has already persisted', function () {
    $repository = probeRepository();

    Cache::forever(generationKeyOf($repository), 'persisted-token');

    expect(CacheGeneration::get($repository))->toBe('persisted-token');
});

it('should replace a persisted token that is not a string', function () {
    $repository = probeRepository();

    Cache::forever(generationKeyOf($repository), 42);

    $token = CacheGeneration::get($repository);

    expect($token)->toBeString()->toHaveLength(16)
        ->and(Cache::get(generationKeyOf($repository)))->toBe($token);
});

// ============================================================================
// Bumping
// ============================================================================

it('should move a repository on to a new token when bumped', function () {
    $repository = probeRepository();

    $token = CacheGeneration::get($repository);

    CacheGeneration::bump($repository);

    $bumped = CacheGeneration::get($repository);

    expect($bumped)->not->toBe($token)
        ->and(Cache::get(generationKeyOf($repository)))->toBe($bumped);

    CacheGeneration::flush();

    expect(CacheGeneration::get($repository))->toBe($bumped);
});

it('should keep the tokens of different repositories apart', function () {
    $first = probeRepository();

    $second = probeRepository();

    $secondToken = CacheGeneration::get($second);

    expect(CacheGeneration::get($first))->not->toBe($secondToken);

    CacheGeneration::bump($first);

    expect(CacheGeneration::get($second))->toBe($secondToken);
});
