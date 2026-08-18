<?php

namespace Webkul\DataTransfer\Helpers\Importers\Concerns;

use Illuminate\Bus\Batch;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Storage;
use Webkul\DataTransfer\Helpers\Import;
use Webkul\DataTransfer\Jobs\Import\ValidateChunk;
use Webkul\DataTransfer\Repositories\ImportRepository;

/**
 * Validates an importer's source in windows, opt in through
 * `$chunkedValidationSupported`: either the browser drives short resumable ones,
 * or they are dispatched as a batch across the worker fleet.
 *
 * A window is validated in isolation, so anything spanning the file — uniqueness
 * above all — is recorded per window through the hooks below and cross-checked
 * once the fragments are merged.
 */
trait ValidatesInChunks
{
    /**
     * Does this importer support chunked/queued validation? Importers opt in by
     * flipping this on; anything that leaves it off keeps the single-pass
     * validateData() untouched, so third-party importers are unaffected.
     */
    protected bool $chunkedValidationSupported = false;

    /**
     * Rows validated per window. Validation is CPU-light, so this comfortably
     * finishes inside a web request while keeping the progress count moving.
     * Raise it for fewer, longer round-trips; lower it for smoother progress.
     */
    protected int $validationChunkSize = 500;

    /**
     * Can this importer validate in windows?
     */
    public function supportsChunkedValidation(): bool
    {
        return $this->chunkedValidationSupported;
    }

    /**
     * Rows per validation window.
     */
    public function getValidationChunkSize(): int
    {
        return $this->validationChunkSize;
    }

    /*
    |--------------------------------------------------------------------------
    | Chunked validation (browser-driven)
    |--------------------------------------------------------------------------
    */

    /**
     * Validate one window of the source and persist its valid rows into import
     * batches, resuming from — and returning — a small state array so a large
     * file can be validated across many short requests instead of one long one.
     *
     * The caller persists the returned state and calls again with it until
     * `done` is true.
     */
    public function validateChunkRows(array $state, int $limit): array
    {
        $source = $this->getSource();

        /**
         * Column/header validation runs once, on the first window. A column error
         * aborts everything — no rows are read and no batches are built, exactly
         * as the single-pass validateData() does.
         */
        if (empty($state['header_done'])) {
            $this->validateColumns();

            if ($this->errorHelper->getErrorsCount()) {
                return array_merge($state, [
                    'offset' => 0,
                    'header_done' => true,
                    'done' => true,
                    'error_items' => $this->mergeErrorItems($state['error_items'] ?? [], $this->errorHelper->getAllErrors()),
                ]);
            }

            /**
             * Clear any batches left behind by a previous validation run.
             */
            $this->importBatchRepository->deleteWhere(['import_id' => $this->import->id]);
        }

        /**
         * Every window runs in a fresh request with nothing loaded, so the
         * storage rows are checked against is reloaded each time. It does not
         * change while an import is being validated, so this is safe.
         */
        $this->prepareForValidation();

        $this->restoreValidationState($state['global'] ?? []);

        $offset = (int) ($state['offset'] ?? 0);

        $batchCarry = $state['batch_carry'] ?? [];

        $this->seekSource($source, $offset);

        /**
         * Validate this window, remembering each row so batches are built only
         * once afterChunkValidated() — which may still invalidate a row — has run.
         */
        $chunkRows = [];

        $processed = 0;

        while (
            $source->valid()
            && $processed < $limit
        ) {
            try {
                $rowData = $source->current();
            } catch (\InvalidArgumentException $e) {
                $source->next();

                continue;
            }

            $rowNumber = $source->getCurrentRowNumber();

            $this->validateRow($rowData, $rowNumber);

            $chunkRows[] = [
                'row_number' => $rowNumber,
                'data' => $rowData,
            ];

            $processed++;

            $offset++;

            $source->next();
        }

        $this->afterChunkValidated();

        $done = ! $source->valid();

        $this->buildBatches($chunkRows, $batchCarry, $done);

        return array_merge($state, [
            'offset' => $offset,
            'global' => $this->captureValidationState(),
            'batch_carry' => $batchCarry,
            'error_items' => $this->mergeErrorItems($state['error_items'] ?? [], $this->errorHelper->getAllErrors()),
            'header_done' => true,
            'done' => $done,
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Queued validation (parallel)
    |--------------------------------------------------------------------------
    */

    /**
     * Kick validation off as a parallel queue batch, one job per window, each
     * writing a fragment that the batch's finally-callback merges and finalises.
     *
     * Returns the row count, or zero when there is nothing to validate in
     * parallel and the caller should finalise inline instead.
     */
    public function queueValidation(): int
    {
        /**
         * Column/header validation runs once, up-front — the isolated row windows
         * do not do it. Any column error aborts before row validation.
         */
        $this->validateColumns();

        $columnErrors = $this->errorHelper->getAllErrors();

        $total = $this->sourceRowCount();

        $disk = Storage::disk('private');

        $disk->deleteDirectory($this->validationFragmentDir());

        $this->writeValidationQueueState([
            'total' => $total,
            'column_errors' => $columnErrors,
        ]);

        if (
            ! empty($columnErrors)
            || $total === 0
        ) {
            return 0;
        }

        /**
         * Clear any batches left behind by a previous validation run before the
         * merge starts building new ones.
         */
        $this->importBatchRepository->deleteWhere(['import_id' => $this->import->id]);

        $importId = $this->import->id;

        $jobs = [];

        for ($offset = 0, $index = 0; $offset < $total; $offset += $this->validationChunkSize, $index++) {
            $jobs[] = new ValidateChunk($importId, $offset, $this->validationChunkSize, $index);
        }

        Bus::batch($jobs)
            ->name('import-validate-'.$importId)
            ->allowFailures()
            ->finally(function (Batch $batch) use ($importId) {
                $import = app(ImportRepository::class)->find($importId);

                if (! $import) {
                    return;
                }

                app(Import::class)
                    ->setImport($import)
                    ->finalizeQueuedValidation();
            })
            ->dispatch();

        return $total;
    }

    /**
     * Validate one window of rows in isolation and return its fragment: the rows
     * that passed (prepared for the database), the errors found, and the values
     * of every file-unique column seen — the last so the merge can enforce the
     * uniqueness this window could not see. No cross-chunk state is read and no
     * batches are built here; both are the merge's job.
     */
    public function validateChunkFragment(int $offset, int $limit): array
    {
        $source = $this->getSource();

        $this->prepareForValidation();

        /**
         * An isolated window starts from nothing: whatever the importer carries
         * across rows is reset so this window's verdicts depend only on its own
         * rows plus the database.
         */
        $this->restoreValidationState([]);

        $this->validatedRows = [];

        $this->seekSource($source, $offset);

        $chunkRows = [];

        $uniqueValues = [];

        $uniqueColumns = $this->fileUniqueColumns();

        $processed = 0;

        while (
            $source->valid()
            && $processed < $limit
        ) {
            try {
                $rowData = $source->current();
            } catch (\InvalidArgumentException $e) {
                $source->next();

                continue;
            }

            $rowNumber = $source->getCurrentRowNumber();

            $this->validateRow($rowData, $rowNumber);

            $chunkRows[] = [
                'row_number' => $rowNumber,
                'data' => $rowData,
            ];

            /**
             * Record this row's value for every file-unique column so the merge
             * can flag a value repeated anywhere in the file, however far apart
             * the two rows are.
             */
            foreach (array_keys($uniqueColumns) as $column) {
                if (
                    isset($rowData[$column])
                    && $rowData[$column] !== ''
                ) {
                    $uniqueValues[$column][] = [
                        'value' => $rowData[$column],
                        'row_number' => $rowNumber,
                        'context' => $this->uniqueRowContext($rowData),
                    ];
                }
            }

            $processed++;

            $source->next();
        }

        $this->afterChunkValidated();

        $validRows = [];

        foreach ($chunkRows as $chunkRow) {
            if ($this->errorHelper->isRowInvalid($chunkRow['row_number'])) {
                continue;
            }

            $validRows[] = [
                'row_number' => $chunkRow['row_number'],
                'data' => $this->prepareRowForDb($chunkRow['data']),
            ];
        }

        return [
            'offset' => $offset,
            'processed' => $processed,
            'valid_rows' => $validRows,
            'error_items' => $this->errorHelper->getAllErrors(),
            'unique_values' => $uniqueValues,
        ];
    }

    /**
     * Has this window already produced its fragment? The queue can hand the same
     * window to a second worker while the first still holds it, and validating it
     * twice would double every count it contributes.
     */
    public function hasValidationFragment(int $chunkIndex): bool
    {
        return Storage::disk('private')->exists($this->validationFragmentDir().'/'.$chunkIndex.'.json');
    }

    /**
     * Persist one validation job's result fragment.
     */
    public function writeValidationFragment(int $chunkIndex, array $fragment): void
    {
        Storage::disk('private')->put(
            $this->validationFragmentDir().'/'.$chunkIndex.'.json',
            json_encode($fragment, JSON_UNESCAPED_SLASHES)
        );
    }

    /**
     * Merge every fragment: cross-check the file-wide unique columns (invisible
     * to an isolated window), then build the import batches from the rows that
     * survived. Returns the combined errors and processed count for the caller
     * to finalise the import record with.
     */
    public function mergeValidationFragments(): array
    {
        $disk = Storage::disk('private');

        $fragments = [];

        foreach ($disk->files($this->validationFragmentDir()) as $file) {
            $fragments[] = json_decode($disk->get($file), true) ?: [];
        }

        /**
         * Process in file order so the first occurrence of a value wins and it is
         * the later row reusing it that gets flagged — matching a single pass.
         */
        usort($fragments, fn ($a, $b) => ($a['offset'] ?? 0) <=> ($b['offset'] ?? 0));

        /**
         * Seed with any column/header errors captured before the windows ran.
         */
        $errorItems = $this->readValidationQueueState()['column_errors'] ?? [];

        $invalidRows = [];

        foreach ($errorItems as $rowNumber => $rowErrors) {
            if (is_numeric($rowNumber)) {
                $invalidRows[$rowNumber] = true;
            }
        }

        $processed = 0;

        foreach ($fragments as $fragment) {
            $processed += $fragment['processed'] ?? 0;

            foreach ($fragment['error_items'] ?? [] as $rowNumber => $rowErrors) {
                $errorItems[$rowNumber] = $rowErrors;

                if (is_numeric($rowNumber)) {
                    $invalidRows[$rowNumber] = true;
                }
            }
        }

        $this->mergeUniqueColumns($fragments, $errorItems, $invalidRows);

        /**
         * Build the batches from every row that survived both its own window and
         * the file-wide checks above.
         */
        $batchCarry = [];

        foreach ($fragments as $fragment) {
            foreach ($fragment['valid_rows'] ?? [] as $validRow) {
                if (isset($invalidRows[$validRow['row_number']])) {
                    continue;
                }

                $batchCarry[] = $validRow['data'];

                if (count($batchCarry) >= self::BATCH_SIZE) {
                    $this->importBatchRepository->create([
                        'import_id' => $this->import->id,
                        'data' => array_splice($batchCarry, 0, self::BATCH_SIZE),
                    ]);
                }
            }
        }

        if (! empty($batchCarry)) {
            $this->importBatchRepository->create([
                'import_id' => $this->import->id,
                'data' => $batchCarry,
            ]);
        }

        return [
            'processed' => $processed,
            'error_items' => $errorItems,
        ];
    }

    /**
     * Progress of the queued validation, read from the fragments on disk rather
     * than the database. Done once the import has been finalised — which the
     * batch's finally-callback does after merging — and so has left the
     * "validating" state.
     */
    public function queuedValidationProgress(): array
    {
        $total = (int) ($this->readValidationQueueState()['total'] ?? 0);

        if ($this->import->state !== Import::STATE_VALIDATING) {
            return [
                'total' => $total,
                'processed' => $total,
                'progress' => 100,
                'done' => true,
            ];
        }

        $disk = Storage::disk('private');

        $processed = 0;

        foreach ($disk->files($this->validationFragmentDir()) as $file) {
            $processed += json_decode($disk->get($file), true)['processed'] ?? 0;
        }

        $processed = $total > 0 ? min($processed, $total) : 0;

        return [
            'total' => $total,
            'processed' => $processed,
            'progress' => $total > 0 ? (int) floor($processed / $total * 100) : 100,
            'done' => false,
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Shared helpers
    |--------------------------------------------------------------------------
    */

    /**
     * Re-hydrate the error helper from an accumulated error list and return the
     * counts the import record needs. Called once validation has finished, on
     * either path.
     */
    public function finalizeChunkedErrors(array $errorItems): array
    {
        foreach ($errorItems as $rowNumber => $rowErrors) {
            $rowNumber = is_numeric($rowNumber) ? (int) $rowNumber : null;

            foreach ($rowErrors as $error) {
                /**
                 * Re-added with a null column so the stored — already formatted —
                 * message is kept verbatim rather than being sprintf()'d a second
                 * time against the column name.
                 */
                $this->errorHelper->addError(
                    $error['code'],
                    $rowNumber,
                    null,
                    $error['message'] ?? null
                );

                if (! is_null($rowNumber)) {
                    $this->errorHelper->addRowToSkip($rowNumber);
                }
            }
        }

        return [
            'invalid_rows_count' => $this->errorHelper->getInvalidRowsCount(),
            'errors_count' => $this->errorHelper->getErrorsCount(),
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Hooks for importers
    |--------------------------------------------------------------------------
    */

    /**
     * Snapshot the cross-row state this importer builds up while validating —
     * the file-wide uniqueness maps, typically. Carried between windows on the
     * chunked path so a chunked pass behaves exactly like a single pass.
     */
    protected function captureValidationState(): array
    {
        return [];
    }

    /**
     * Restore a snapshot taken by captureValidationState().
     */
    protected function restoreValidationState(array $state): void {}

    /**
     * Run whatever the importer defers until a whole window has been validated —
     * typically a single database round trip for the window's values rather than
     * one per row.
     */
    protected function afterChunkValidated(): void {}

    /**
     * Columns whose values must be unique across the entire file, as
     * `column => error code`. An isolated chunk cannot see the other chunks, so
     * these are recorded per chunk and cross-checked in mergeValidationFragments().
     * The first row to use a value keeps it; later rows are flagged.
     */
    protected function fileUniqueColumns(): array
    {
        return [];
    }

    /**
     * The part of a row a duplicate message may need in order to name the row
     * that claimed the value first (a product's sku, for instance). Kept small —
     * it is written into every fragment.
     */
    protected function uniqueRowContext(array $rowData): array
    {
        return [];
    }

    /**
     * Message for a row that reused a value already taken earlier in the file.
     * `$context` is what uniqueRowContext() returned for that first row.
     */
    protected function duplicateValueMessage(string $column, string $value, array $context): ?string
    {
        return null;
    }

    /*
    |--------------------------------------------------------------------------
    | Internals
    |--------------------------------------------------------------------------
    */

    /**
     * Cross-check the file-unique columns across every fragment and flag any row
     * that reused a value an earlier row had already taken.
     */
    protected function mergeUniqueColumns(array $fragments, array &$errorItems, array &$invalidRows): void
    {
        $uniqueColumns = $this->fileUniqueColumns();

        if (empty($uniqueColumns)) {
            return;
        }

        $seen = [];

        foreach ($fragments as $fragment) {
            foreach ($uniqueColumns as $column => $errorCode) {
                foreach ($fragment['unique_values'][$column] ?? [] as $entry) {
                    $value = (string) $entry['value'];

                    if (! isset($seen[$column][$value])) {
                        $seen[$column][$value] = $entry['context'] ?? [];

                        continue;
                    }

                    /**
                     * A row already flagged for something else needs no second
                     * complaint about the same value.
                     */
                    if (isset($invalidRows[$entry['row_number']])) {
                        continue;
                    }

                    $errorItems[$entry['row_number']][] = [
                        'code' => $errorCode,
                        'column' => $column,
                        'message' => $this->duplicateValueMessage($column, $value, $seen[$column][$value]),
                    ];

                    $invalidRows[$entry['row_number']] = true;
                }
            }
        }
    }

    /**
     * Merge one window's row errors into the accumulated list. Row numbers are
     * unique per window, so keys never really collide — bar the null-keyed column
     * errors, which only occur on the aborting first window.
     */
    protected function mergeErrorItems(array $accumulated, array $chunkErrors): array
    {
        return array_replace($accumulated, $chunkErrors);
    }

    /**
     * Persist the rows that survived validation into import batches, carrying a
     * partial batch over to the next window and flushing it on the last one.
     */
    protected function buildBatches(array $chunkRows, array &$batchCarry, bool $flush): void
    {
        foreach ($chunkRows as $chunkRow) {
            if ($this->errorHelper->isRowInvalid($chunkRow['row_number'])) {
                continue;
            }

            $batchCarry[] = $this->prepareRowForDb($chunkRow['data']);

            if (count($batchCarry) >= self::BATCH_SIZE) {
                $this->importBatchRepository->create([
                    'import_id' => $this->import->id,
                    'data' => array_splice($batchCarry, 0, self::BATCH_SIZE),
                ]);
            }
        }

        if (
            $flush
            && ! empty($batchCarry)
        ) {
            $this->importBatchRepository->create([
                'import_id' => $this->import->id,
                'data' => $batchCarry,
            ]);

            $batchCarry = [];
        }
    }

    /**
     * Move the source to a row offset. Sources only read forward, so this rewinds
     * and skips — cheap, since skipping only parses and never validates.
     */
    protected function seekSource($source, int $offset): void
    {
        $source->rewind();

        for ($skipped = 0; $skipped < $offset && $source->valid(); $skipped++) {
            $source->next();
        }
    }

    /**
     * Count the source's data rows. A forward parse, no validation.
     */
    protected function sourceRowCount(): int
    {
        $source = $this->getSource();

        $source->rewind();

        $count = 0;

        while ($source->valid()) {
            $count++;

            $source->next();
        }

        return $count;
    }

    /**
     * Directory holding this import's per-job validation fragments.
     */
    protected function validationFragmentDir(): string
    {
        return 'imports/'.$this->import->id.'/processed/validation-fragments';
    }

    /**
     * Location of the queued-validation state file, which holds the row total and
     * any column errors found before the windows were dispatched.
     */
    protected function validationQueueStatePath(): string
    {
        return 'imports/'.$this->import->id.'/processed/validation-queue.json';
    }

    /**
     * Read the queued-validation state.
     */
    protected function readValidationQueueState(): array
    {
        $disk = Storage::disk('private');

        if (! $disk->exists($this->validationQueueStatePath())) {
            return [];
        }

        return json_decode($disk->get($this->validationQueueStatePath()), true) ?: [];
    }

    /**
     * Persist the queued-validation state.
     */
    protected function writeValidationQueueState(array $state): void
    {
        Storage::disk('private')->put(
            $this->validationQueueStatePath(),
            json_encode($state, JSON_UNESCAPED_SLASHES)
        );
    }
}
