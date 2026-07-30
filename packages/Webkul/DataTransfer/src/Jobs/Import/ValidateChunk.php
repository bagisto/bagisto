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
 * Validates one window of an import's rows in isolation and writes the result as
 * a fragment.
 *
 * The batch's finally-callback merges the fragments, cross-checks the file-wide
 * rules no single window can see, builds the import batches and finalises the
 * record.
 */
class ValidateChunk implements ShouldQueue
{
    use Batchable, BoundedByRetryAfter, Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * A row-level failure is recorded in the fragment rather than thrown, so the
     * job never needs a retry.
     */
    public int $tries = 1;

    /**
     * Bounded by the connection's `retry_after` so the queue can never hand this
     * window to a second worker while this one still holds it.
     */
    public int $timeout;

    /**
     * Create a new job instance.
     */
    public function __construct(
        protected int $importId,
        protected int $offset,
        protected int $limit,
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

        $helper = app(ImportHelper::class)->setImport($import);

        $importer = $helper->getTypeImporter()->setSource($helper->getSource());

        if (! $importer->supportsChunkedValidation()) {
            return;
        }

        /**
         * The queue can hand the same window to a second worker while the first
         * is still validating it. A window that already produced its fragment is
         * not validated again — otherwise every row in it is counted twice.
         */
        if ($importer->hasValidationFragment($this->chunkIndex)) {
            return;
        }

        $importer->writeValidationFragment(
            $this->chunkIndex,
            $importer->validateChunkFragment($this->offset, $this->limit)
        );
    }
}
