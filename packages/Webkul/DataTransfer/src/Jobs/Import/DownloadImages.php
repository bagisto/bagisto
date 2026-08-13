<?php

namespace Webkul\DataTransfer\Jobs\Import;

use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Webkul\DataTransfer\Helpers\Import as ImportHelper;
use Webkul\DataTransfer\Jobs\Concerns\BoundedByRetryAfter;
use Webkul\DataTransfer\Repositories\ImportRepository;

/**
 * Fetches one wave of an import's remote images and writes the results as a
 * fragment.
 *
 * The batch's finally-callback folds the fragments into the manifest that the
 * row-writing phase reads.
 */
class DownloadImages implements ShouldQueue
{
    use Batchable, BoundedByRetryAfter, Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * A fetch failure is recorded per image rather than thrown, so the job itself
     * never needs a retry.
     */
    public int $tries = 1;

    /**
     * Bounded by the connection's `retry_after` so the queue can never hand this
     * wave to a second worker while this one still holds it.
     */
    public int $timeout;

    /**
     * Create a new job instance.
     */
    public function __construct(
        protected int $importId,
        protected array $urls,
        protected int $chunkIndex,
    ) {
        $this->timeout = static::timeoutWithinRetryAfter();
    }

    /**
     * Execute the job.
     */
    public function handle(ImportRepository $importRepository): void
    {
        if ($this->batch()?->cancelled()) {
            return;
        }

        $import = $importRepository->find($this->importId);

        if (! $import) {
            return;
        }

        $importer = app(ImportHelper::class)
            ->setImport($import)
            ->getTypeImporter();

        if (! method_exists($importer, 'downloadImageUrls')) {
            return;
        }

        /**
         * The queue can hand the same wave to a second worker while the first is
         * still fetching it. A wave that already produced its fragment is not
         * fetched again — otherwise every image in it is pulled over the network
         * twice, which is the cost this whole phase exists to avoid.
         */
        if ($importer->hasImageFragment($this->chunkIndex)) {
            return;
        }

        $importer->writeImageFragment(
            $this->chunkIndex,
            $importer->downloadImageUrls($this->urls)
        );
    }
}
