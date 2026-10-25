<?php

use Illuminate\Console\Scheduling\Event;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use League\Flysystem\Filesystem;
use Webkul\Product\Repositories\SearchRepository;
use Webkul\Product\Tests\Fixtures\UndeletableDirectoriesAdapter;

/**
 * Store an image search upload in the folder of the given day.
 */
function searchImageOn(string $day, string $name = 'shoe.png'): string
{
    $path = SearchRepository::SEARCH_IMAGES_DIRECTORY.'/'.$day.'/'.$name;

    Storage::put($path, 'image');

    return $path;
}

beforeEach(function () {
    Storage::fake();

    $this->freezeTime();
});

// ============================================================================
// Pruning
// ============================================================================

it('should delete the image search uploads of every day before today, keeping today', function () {
    $older = searchImageOn(now()->subDays(2)->toDateString());

    $yesterday = searchImageOn(now()->subDay()->toDateString());

    $today = searchImageOn(now()->toDateString());

    $this->artisan('search-images:prune')->assertSuccessful();

    expect(Storage::exists($older))->toBeFalse()
        ->and(Storage::exists($yesterday))->toBeFalse()
        ->and(Storage::exists($today))->toBeTrue();
});

it('should delete the folder image search uploads were kept in before they were filed by day', function () {
    Storage::put('product-search/old-search.png', 'image');

    $this->artisan('search-images:prune')->assertSuccessful();

    expect(Storage::directoryExists('product-search'))->toBeFalse();
});

it('should leave a folder that is not a day of uploads alone', function (string $folder) {
    $kept = SearchRepository::SEARCH_IMAGES_DIRECTORY.'/'.$folder.'/notes.txt';

    Storage::put($kept, 'kept');

    $this->artisan('search-images:prune')->assertSuccessful();

    expect(Storage::exists($kept))->toBeTrue();
})->with([
    'folder of another name' => ['keep'],
    'folder named after a past day with more after it' => [fn () => now()->subDay()->toDateString().'-backup'],
]);

it('should prune nothing when no image search was ever made', function () {
    $this->artisan('search-images:prune')->assertSuccessful();

    expect(Storage::directories(SearchRepository::SEARCH_IMAGES_DIRECTORY))->toBe([]);
});

it('should fail, naming the folder in its output and in the log, when a day of uploads cannot be deleted', function () {
    Log::spy();

    $adapter = new UndeletableDirectoriesAdapter(Storage::fake()->path(''));

    Storage::set(config('filesystems.default'), new FilesystemAdapter(new Filesystem($adapter), $adapter));

    $yesterday = searchImageOn(now()->subDay()->toDateString());

    $this->artisan('search-images:prune')
        ->expectsOutputToContain(dirname($yesterday))
        ->assertFailed();

    Log::shouldHaveReceived('error')
        ->once()
        ->withArgs(fn (string $message) => str_contains($message, dirname($yesterday)));

    expect(Storage::exists($yesterday))->toBeTrue();
});

// ============================================================================
// Schedule
// ============================================================================

it('should prune the image search uploads every day shortly after midnight', function () {
    app()->forgetInstance(Schedule::class);

    $event = collect(app(Schedule::class)->events())
        ->first(fn (Event $event) => str_contains($event->command, 'search-images:prune'));

    expect($event)->not->toBeNull()
        ->expression->toBe('5 0 * * *');
});
