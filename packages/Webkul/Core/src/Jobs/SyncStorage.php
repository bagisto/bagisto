<?php

namespace Webkul\Core\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Webkul\Core\Filesystem\StorageSynchronizer;

class SyncStorage implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(
        protected ?string $source = null,
        protected ?string $target = null
    ) {}

    /**
     * Copy whatever the target disk is missing, and record what the run did.
     */
    public function handle(StorageSynchronizer $synchronizer): void
    {
        $summary = $synchronizer->sync($this->source, $this->target);

        Log::info('Storage sync finished.', $summary);
    }
}
