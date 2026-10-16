<?php

namespace Webkul\Core\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Webkul\Core\Enums\SupportedFilesystemEnum;
use Webkul\Core\Filesystem\StorageSynchronizer;

class StorageSync extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bagisto:storage:sync
        {--from= : The disk to copy from, defaulting to the local public disk}
        {--to= : The disk to copy to, defaulting to the one the admin has chosen}
        {--pretend : Report what would be copied without writing anything}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Copy existing media onto the configured storage disk';

    /**
     * Execute the console command.
     */
    public function handle(StorageSynchronizer $synchronizer): int
    {
        $source = $this->option('from') ?: SupportedFilesystemEnum::default()->value;

        $target = $this->option('to') ?: config('filesystems.default');

        if ($source === $target) {
            $this->warn("Nothing to do: '{$source}' is already the configured disk.");

            return self::SUCCESS;
        }

        if (
            ! $this->diskExists($source)
            || ! $this->diskExists($target)
        ) {
            return self::FAILURE;
        }

        $this->line("Copying from <info>{$source}</info> to <info>{$target}</info>".($this->option('pretend') ? ' (pretending)' : '').'.');

        $bar = $this->output->createProgressBar($this->countFiles($source));

        $summary = $synchronizer->sync(
            $source,
            $target,
            (bool) $this->option('pretend'),
            fn () => $bar->advance()
        );

        $bar->finish();

        $this->newLine(2);

        $this->report($summary);

        return $summary['failed'] > 0 ? self::FAILURE : self::SUCCESS;
    }

    /**
     * Whether the named disk is configured, complaining to the console when it is not.
     */
    protected function diskExists(string $disk): bool
    {
        if (config("filesystems.disks.{$disk}")) {
            return true;
        }

        $this->error("There is no '{$disk}' disk in config/filesystems.php.");

        return false;
    }

    /**
     * How many files the source holds, so the progress bar knows where it is going.
     */
    protected function countFiles(string $disk): int
    {
        return count(Storage::disk($disk)->allFiles());
    }

    /**
     * Print what the run did, and anything it could not do.
     */
    protected function report(array $summary): void
    {
        $this->table(
            ['Found', 'Copied', 'Already there', 'Failed'],
            [[$summary['total'], $summary['copied'], $summary['skipped'], $summary['failed']]]
        );

        if (empty($summary['failures'])) {
            $this->info('Done.');

            return;
        }

        $this->error('These files could not be copied:');

        foreach ($summary['failures'] as $path => $message) {
            $this->line("  <comment>{$path}</comment> — {$message}");
        }
    }
}
