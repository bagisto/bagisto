<?php

namespace Webkul\Product\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Webkul\Product\Repositories\SearchRepository;

class PruneSearchImages extends Command
{
    /**
     * The folder image search uploads were kept in before they were filed by day.
     */
    protected const LEGACY_DIRECTORY = 'product-search';

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'search-images:prune';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete the image search uploads of every day before today';

    /**
     * Delete the folder of each day before today, keeping today's, whose uploads a results page may still show,
     * and the folder uploads were kept in before, failing and logging any folder it cannot delete.
     */
    public function handle(): int
    {
        $today = now()->toDateString();

        $directories = array_filter(
            Storage::directories(SearchRepository::SEARCH_IMAGES_DIRECTORY),
            fn (string $directory) => $this->isDayBefore($directory, $today)
        );

        if (Storage::directoryExists(self::LEGACY_DIRECTORY)) {
            $directories[] = self::LEGACY_DIRECTORY;
        }

        $failed = array_filter($directories, fn (string $directory) => ! Storage::deleteDirectory($directory));

        foreach ($failed as $directory) {
            $message = "Could not delete the image search folder [{$directory}].";

            $this->components->error($message);

            Log::error($message);
        }

        $this->components->info('Pruned '.(count($directories) - count($failed)).' image search folder(s).');

        return empty($failed) ? self::SUCCESS : self::FAILURE;
    }

    /**
     * Whether a folder holds the uploads of a day before the given one, named for that day.
     */
    protected function isDayBefore(string $directory, string $today): bool
    {
        $day = basename($directory);

        return preg_match('/^\d{4}-\d{2}-\d{2}$/', $day) === 1
            && $day < $today;
    }
}
