<?php

namespace Webkul\Core\Filesystem;

use Closure;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Support\Facades\Storage;
use Webkul\Core\Enums\SupportedFilesystemEnum;

class StorageSynchronizer
{
    /**
     * Copy every file the source disk holds onto the target, leaving the source untouched.
     *
     * A file the target already has is passed over, so an interrupted run is resumed by
     * running it again rather than started from the beginning.
     */
    public function sync(?string $source = null, ?string $target = null, bool $pretend = false, ?Closure $progress = null): array
    {
        $source ??= SupportedFilesystemEnum::default()->value;

        $target ??= config('filesystems.default');

        $progress ??= fn () => null;

        $summary = [
            'source' => $source,
            'target' => $target,
            'total' => 0,
            'copied' => 0,
            'skipped' => 0,
            'failed' => 0,
            'failures' => [],
        ];

        if ($source === $target) {
            return $summary;
        }

        $from = Storage::disk($source);

        $to = Storage::disk($target);

        $paths = $from->allFiles();

        $summary['total'] = count($paths);

        foreach ($paths as $path) {
            if ($to->exists($path)) {
                $summary['skipped']++;

                $progress($path, 'skipped');

                continue;
            }

            if ($pretend) {
                $summary['copied']++;

                $progress($path, 'copied');

                continue;
            }

            try {
                $this->copy($from, $to, $path);

                $summary['copied']++;

                $progress($path, 'copied');
            } catch (\Throwable $e) {
                $summary['failed']++;

                $summary['failures'][$path] = $e->getMessage();

                $progress($path, 'failed');
            }
        }

        return $summary;
    }

    /**
     * How many files the target is still missing, without copying any of them.
     */
    public function pending(?string $source = null, ?string $target = null): int
    {
        return $this->sync($source, $target, true)['copied'];
    }

    /**
     * Stream one file across, so a large image is never held in memory in full.
     */
    protected function copy(Filesystem $from, Filesystem $to, string $path): void
    {
        $stream = $from->readStream($path);

        if (
            $stream === false
            || is_null($stream)
        ) {
            throw new \RuntimeException('The source disk could not be read.');
        }

        try {
            if ($to->writeStream($path, $stream) === false) {
                throw new \RuntimeException('The target disk refused the write.');
            }
        } finally {
            if (is_resource($stream)) {
                fclose($stream);
            }
        }
    }
}
