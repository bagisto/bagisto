<?php

namespace Webkul\DataTransfer\Helpers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Webkul\DataTransfer\Contracts\Import as ImportContract;
use Webkul\DataTransfer\Contracts\ImportBatch as ImportBatchContract;
use Webkul\DataTransfer\Helpers\Importers\AbstractImporter;
use Webkul\DataTransfer\Helpers\Sources\AbstractSource;
use Webkul\DataTransfer\Helpers\Sources\CSV as CSVSource;
use Webkul\DataTransfer\Helpers\Sources\XLS as XLSSource;
use Webkul\DataTransfer\Helpers\Sources\XLSX as XLSXSource;
use Webkul\DataTransfer\Helpers\Sources\XML as XMLSource;
use Webkul\DataTransfer\Repositories\ImportBatchRepository;
use Webkul\DataTransfer\Repositories\ImportRepository;

class Import
{
    /**
     * Import state for pending import.
     *
     * @var string
     */
    public const STATE_PENDING = 'pending';

    /**
     * Import state while a queued validation is in flight.
     *
     * Persisted so reopening the page resumes polling the running validation
     * instead of offering to start it again.
     *
     * @var string
     */
    public const STATE_VALIDATING = 'validating';

    /**
     * Import state for validated import.
     *
     * @var string
     */
    public const STATE_VALIDATED = 'validated';

    /**
     * Import state while the import's remote images are being fetched.
     *
     * @var string
     */
    public const STATE_DOWNLOADING = 'downloading';

    /**
     * Import state for processing import.
     *
     * @var string
     */
    public const STATE_PROCESSING = 'processing';

    /**
     * Import state for processed import.
     *
     * @var string
     */
    public const STATE_PROCESSED = 'processed';

    /**
     * Import state for linking import.
     *
     * @var string
     */
    public const STATE_LINKING = 'linking';

    /**
     * Import state for linked import.
     *
     * @var string
     */
    public const STATE_LINKED = 'linked';

    /**
     * Import state for indexing import.
     *
     * @var string
     */
    public const STATE_INDEXING = 'indexing';

    /**
     * Import state for indexed import.
     *
     * @var string
     */
    public const STATE_INDEXED = 'indexed';

    /**
     * Import state for completed import.
     *
     * @var string
     */
    public const STATE_COMPLETED = 'completed';

    /**
     * Validation strategy for skipping the error during the import process.
     *
     * @var string
     */
    public const VALIDATION_STRATEGY_SKIP_ERRORS = 'skip-errors';

    /**
     * Validation strategy for stopping the import process on error.
     *
     * @var string
     */
    public const VALIDATION_STRATEGY_STOP_ON_ERROR = 'stop-on-errors';

    /**
     * Action constant for updating/creating for the resource.
     *
     * @var string
     */
    public const ACTION_APPEND = 'append';

    /**
     * Action constant for deleting the resource.
     *
     * @var string
     */
    public const ACTION_DELETE = 'delete';

    /**
     * Images come from links in the sheet, and are fetched before the import runs.
     *
     * @var string
     */
    public const IMAGE_SOURCE_URL = 'url';

    /**
     * Images come from an archive uploaded with the import, unpacked into the
     * import's own folder.
     *
     * @var string
     */
    public const IMAGE_SOURCE_UPLOAD = 'upload';

    /**
     * Images were placed on the server by hand, in a directory under
     * `storage/app/import`. The original behaviour, and the default.
     *
     * @var string
     */
    public const IMAGE_SOURCE_DIRECTORY = 'directory';

    /**
     * Row numbers listed on a single error message before the rest are summarised.
     *
     * @var int
     */
    public const ERROR_ROWS_PREVIEW = 10;

    /**
     * Distinct error messages kept in the on-screen summary.
     *
     * @var int
     */
    public const ERROR_MESSAGES_PREVIEW = 10;

    /**
     * Import instance.
     */
    protected ImportContract $import;

    /**
     * Type importer instance.
     *
     * @var AbstractImporter
     */
    protected $typeImporter;

    /**
     * Create a new helper instance.
     *
     * @return void
     */
    public function __construct(
        protected ImportRepository $importRepository,
        protected ImportBatchRepository $importBatchRepository,
        protected Error $errorHelper
    ) {}

    /**
     * Set import instance.
     */
    public function setImport(ImportContract $import): self
    {
        $this->import = $import;

        return $this;
    }

    /**
     * Returns import instance.
     */
    public function getImport(): ImportContract
    {
        return $this->import;
    }

    /**
     * Returns error helper instance.
     *
     * @return Error
     */
    public function getErrorHelper()
    {
        return $this->errorHelper;
    }

    /**
     * Returns source helper instance.
     */
    public function getSource(): AbstractSource
    {
        if (Str::endsWith($this->import->file_path, '.csv')) {
            return new CSVSource(
                $this->import->file_path,
                $this->import->field_separator,
            );
        }

        if (Str::endsWith($this->import->file_path, '.xml')) {
            return new XMLSource(
                $this->import->file_path,
                $this->import->field_separator,
            );
        }

        if (Str::endsWith($this->import->file_path, '.xls')) {
            return new XLSSource(
                $this->import->file_path,
                $this->import->field_separator,
            );
        }

        if (Str::endsWith($this->import->file_path, '.xlsx')) {
            return new XLSXSource(
                $this->import->file_path,
                $this->import->field_separator,
            );
        }

        throw new \InvalidArgumentException("Unsupported file type: {$this->import->file_path}");
    }

    /**
     * Validates import and returns validation result.
     */
    public function validate(): bool
    {
        try {
            $source = $this->getSource();

            $typeImporter = $this->getTypeImporter()->setSource($source);

            $typeImporter->validateData();
        } catch (\Throwable $e) {
            report($e);

            $this->errorHelper->addError(
                AbstractImporter::ERROR_CODE_SYSTEM_EXCEPTION,
                null,
                null,
                trans('data_transfer::app.validation.errors.system')
            );
        }

        $import = $this->importRepository->update([
            'state' => self::STATE_VALIDATED,
            'processed_rows_count' => $this->getProcessedRowsCount(),
            'invalid_rows_count' => $this->errorHelper->getInvalidRowsCount(),
            'errors_count' => $this->errorHelper->getErrorsCount(),
            'errors' => $this->getFormattedErrors(),
            'error_file_path' => $this->uploadErrorReport(),
        ], $this->import->id);

        $this->setImport($import);

        return $this->isValid();
    }

    /**
     * Validate one window of the source file and return progress, keeping
     * resumable state between calls.
     *
     * A file large enough to outlast a request is validated in windows instead.
     * The last one finalises the record exactly as validate() would.
     */
    public function validateChunk(?int $limit = null): array
    {
        $importer = $this->getTypeImporter()->setSource($this->getSource());

        /**
         * Not every importer supports chunked validation. Fall back to the
         * synchronous pass for those and report a single, already-finished step.
         */
        if (! $importer->supportsChunkedValidation()) {
            return $this->validateWholeFile();
        }

        $state = $this->readValidationState();

        /**
         * Count the source's data rows once, on the first window, so the screen
         * has a denominator. A cheap forward parse — no validation.
         */
        if (! isset($state['total'])) {
            $state['total'] = $this->countSourceRows();
        }

        $total = (int) $state['total'];

        try {
            $state = $importer->validateChunkRows($state, $limit ?? $importer->getValidationChunkSize());
        } catch (\Throwable $e) {
            report($e);

            $this->errorHelper->addError(
                AbstractImporter::ERROR_CODE_SYSTEM_EXCEPTION,
                null,
                null,
                trans('data_transfer::app.validation.errors.system')
            );

            $state['done'] = true;

            $state['error_items'] = array_replace($state['error_items'] ?? [], $this->errorHelper->getAllErrors());
        }

        if (! empty($state['done'])) {
            $this->finalizeChunkedValidation($state);

            $this->clearValidationState();
        } else {
            $this->writeValidationState($state);
        }

        return [
            'processed' => min((int) ($state['offset'] ?? 0), $total),
            'total' => $total,
            'invalid' => $this->countInvalidRows($state['error_items'] ?? []),
            'done' => ! empty($state['done']),
            'is_valid' => empty($state['done']) ? true : $this->isValid(),
            'import' => empty($state['done']) ? null : $this->getImport()->unsetRelations(),
        ];
    }

    /**
     * Dispatch validation as a background, parallel queue batch and return the
     * row total. Zero means there was nothing to fan out — a bad header or an
     * empty file — and the record has already been finalised inline.
     */
    public function queueValidation(): int
    {
        $importer = $this->getTypeImporter()->setSource($this->getSource());

        if (! $importer->supportsChunkedValidation()) {
            $this->validate();

            return 0;
        }

        /**
         * Flagged before the batch goes out, not after: on a synchronous queue the
         * jobs run inside the dispatch, and this would land on top of the state
         * they left.
         */
        $import = $this->importRepository->update([
            'state' => self::STATE_VALIDATING,
        ], $this->import->id);

        $this->setImport($import);

        $total = $importer->queueValidation();

        /**
         * A bad header or an empty file — nothing was fanned out, so finalise
         * here instead of waiting for a callback that will never come.
         */
        if ($total === 0) {
            $this->finalizeQueuedValidation();
        }

        return $total;
    }

    /**
     * Progress of a queued validation, for the screen.
     */
    public function queuedValidationProgress(): array
    {
        $importer = $this->getTypeImporter();

        if (! $importer->supportsChunkedValidation()) {
            return [
                'total' => 0,
                'processed' => 0,
                'progress' => 100,
                'done' => true,
            ];
        }

        return $importer->queuedValidationProgress();
    }

    /**
     * Finalise a parallel (queued) validation: merge every window's fragment —
     * cross-checking the file-wide rules and building the import batches — then
     * write the record exactly as the chunked path does. Runs from the validation
     * batch's finally-callback.
     */
    public function finalizeQueuedValidation(): void
    {
        $importer = $this->getTypeImporter()->setSource($this->getSource());

        /**
         * Only an importer that fans validation out leaves fragments to merge.
         */
        if (! $importer->supportsChunkedValidation()) {
            $this->validate();

            return;
        }

        $this->resetErrorHelper();

        $merged = $importer->mergeValidationFragments();

        $counts = $importer->finalizeChunkedErrors($merged['error_items'] ?? []);

        $import = $this->importRepository->update([
            'state' => self::STATE_VALIDATED,
            'processed_rows_count' => (int) ($merged['processed'] ?? 0),
            'invalid_rows_count' => $counts['invalid_rows_count'],
            'errors_count' => $counts['errors_count'],
            'errors' => $this->getFormattedErrors(),
            'error_file_path' => $this->uploadErrorReport(),
        ], $this->import->id);

        $this->setImport($import);
    }

    /*
    |--------------------------------------------------------------------------
    | Image download phase
    |--------------------------------------------------------------------------
    |
    | Only importers that can carry remote image references implement these; for
    | everything else each reports a single, already-finished step so the screen
    | can walk the same phases regardless.
    |
    */

    /**
     * Does this kind of import deal with images at all?
     *
     * Read from the importer rather than from a list of types kept beside it, so
     * an importer that gains or loses images says so itself.
     */
    public static function typeSupportsImages(?string $type): bool
    {
        $importer = config('importers.'.$type.'.importer');

        return is_string($importer)
            && method_exists($importer, 'downloadImagesBatch');
    }

    /**
     * Whether an import is part-way through a phase and has progress worth returning to.
     */
    public static function isInProgress(?string $state): bool
    {
        return in_array($state, [
            self::STATE_VALIDATING,
            self::STATE_DOWNLOADING,
            self::STATE_PROCESSING,
            self::STATE_LINKING,
            self::STATE_INDEXING,
        ]);
    }

    /**
     * Does this import have an image-download phase at all?
     *
     * Only images given as links have to be fetched. An uploaded archive is
     * already unpacked on disk, and a server directory was always there — both
     * are read directly while the rows are written.
     */
    public function hasImagePhase(): bool
    {
        return method_exists($this->getTypeImporter(), 'downloadImagesBatch')
            && $this->import->action != self::ACTION_DELETE
            && $this->import->image_source == self::IMAGE_SOURCE_URL;
    }

    /**
     * Fetch the next wave of the import's remote images and return progress.
     */
    public function downloadImages(): array
    {
        if (! $this->hasImagePhase()) {
            return $this->noImagesProgress();
        }

        return $this->getTypeImporter()->downloadImagesBatch();
    }

    /**
     * Dispatch the image download as a parallel queue batch, returning the image
     * total. Zero means there was nothing to fetch.
     */
    public function queueImageDownload(): int
    {
        if (! $this->hasImagePhase()) {
            return 0;
        }

        /**
         * Flagged before the batch goes out, for the same reason as the queued
         * validation: on a synchronous queue the jobs run inside the dispatch,
         * so a state written afterwards would land on top of whatever they left.
         */
        $import = $this->importRepository->update([
            'state' => self::STATE_DOWNLOADING,
        ], $this->import->id);

        $this->setImport($import);

        $total = $this->getTypeImporter()->queueImageDownload();

        /**
         * URL mode with no URLs to fetch dispatches nothing, and nothing would
         * then move the record on — so it is handed straight back.
         */
        if (! $total) {
            $this->setImport($this->importRepository->update([
                'state' => self::STATE_VALIDATED,
            ], $this->import->id));
        }

        return $total;
    }

    /**
     * Progress of a queued image download.
     */
    public function queuedImageProgress(): array
    {
        if (! $this->hasImagePhase()) {
            return $this->noImagesProgress();
        }

        return $this->getTypeImporter()->queuedImageProgress();
    }

    /**
     * How many images were fetched and how many could not be, for the summary.
     */
    public function imageStats(): array
    {
        if (! $this->hasImagePhase()) {
            return [
                'total' => 0,
                'downloaded' => 0,
                'failed' => 0,
            ];
        }

        return $this->getTypeImporter()->imageReportStats();
    }

    /**
     * Starts import process.
     */
    public function isValid(): bool
    {
        if ($this->isErrorLimitExceeded()) {
            return false;
        }

        if ($this->import->processed_rows_count <= $this->import->invalid_rows_count) {
            return false;
        }

        return true;
    }

    /**
     * Check if error limit has been exceeded.
     */
    public function isErrorLimitExceeded(): bool
    {
        if (
            $this->import->validation_strategy == self::VALIDATION_STRATEGY_STOP_ON_ERROR
            && $this->import->errors_count > $this->import->allowed_errors
        ) {
            return true;
        }

        return false;
    }

    /**
     * Starts import process.
     */
    public function start(?ImportBatchContract $importBatch = null): bool
    {
        DB::beginTransaction();

        try {
            $typeImporter = $this->getTypeImporter();

            $typeImporter->importData($importBatch);
        } catch (\Exception $e) {
            /**
             * Rollback transaction
             */
            DB::rollBack();

            throw $e;
        } finally {
            /**
             * Commit transaction
             */
            DB::commit();
        }

        return true;
    }

    /**
     * Link import resources.
     */
    public function link(ImportBatchContract $importBatch): bool
    {
        DB::beginTransaction();

        try {
            $typeImporter = $this->getTypeImporter();

            $typeImporter->linkData($importBatch);
        } catch (\Exception $e) {
            /**
             * Rollback transaction
             */
            DB::rollBack();

            throw $e;
        } finally {
            /**
             * Commit transaction
             */
            DB::commit();
        }

        return true;
    }

    /**
     * Index import resources.
     */
    public function index(ImportBatchContract $importBatch): bool
    {
        DB::beginTransaction();

        try {
            $typeImporter = $this->getTypeImporter();

            $typeImporter->indexData($importBatch);
        } catch (\Exception $e) {
            /**
             * Rollback transaction
             */
            DB::rollBack();

            throw $e;
        } finally {
            /**
             * Commit transaction
             */
            DB::commit();
        }

        return true;
    }

    /**
     * Started the import process.
     */
    public function started(): void
    {
        $import = $this->importRepository->update([
            'state' => self::STATE_PROCESSING,
            'started_at' => now(),
            'summary' => [],
        ], $this->import->id);

        $this->setImport($import);

        Event::dispatch('data_transfer.imports.started', $import);
    }

    /**
     * Take ownership of a validated import, reporting whether this caller got it.
     *
     * One request dispatches every batch, so it must happen once: a second one
     * would run the whole chain over the same rows, and the two would deadlock.
     */
    public function claimForProcessing(): bool
    {
        /**
         * Both states sit in front of the create step: an import with images to
         * fetch reaches it from `downloading`, one without from `validated`.
         * Everything later is deliberately excluded — that is what stops a
         * second chain being dispatched over a run already under way.
         */
        $claimed = $this->importRepository->transitionState(
            $this->import->id,
            [self::STATE_VALIDATED, self::STATE_DOWNLOADING],
            self::STATE_PROCESSING,
            [
                'started_at' => now(),
                'summary' => json_encode([]),
            ]
        );

        if (! $claimed) {
            return false;
        }

        $this->setImport($this->importRepository->find($this->import->id));

        Event::dispatch('data_transfer.imports.started', $this->import);

        return true;
    }

    /**
     * Started the import linking process.
     */
    public function linking(): void
    {
        $import = $this->importRepository->update([
            'state' => self::STATE_LINKING,
        ], $this->import->id);

        $this->setImport($import);

        Event::dispatch('data_transfer.imports.linking', $import);
    }

    /**
     * Started the import indexing process.
     */
    public function indexing(): void
    {
        $import = $this->importRepository->update([
            'state' => self::STATE_INDEXING,
        ], $this->import->id);

        $this->setImport($import);

        Event::dispatch('data_transfer.imports.indexing', $import);
    }

    /**
     * Start the import process.
     */
    public function completed(): void
    {
        $summary = $this->importBatchRepository
            ->select(
                DB::raw('SUM(json_unquote(json_extract(summary, \'$."created"\'))) AS created'),
                DB::raw('SUM(json_unquote(json_extract(summary, \'$."updated"\'))) AS updated'),
                DB::raw('SUM(json_unquote(json_extract(summary, \'$."deleted"\'))) AS deleted'),
            )
            ->where('import_id', $this->import->id)
            ->groupBy('import_id')
            ->first()
            ->toArray();

        $import = $this->importRepository->update([
            'state' => self::STATE_COMPLETED,
            'summary' => $summary,
            'completed_at' => now(),
        ], $this->import->id);

        $this->setImport($import);

        Event::dispatch('data_transfer.imports.completed', $import);
    }

    /**
     * Returns import stats.
     */
    public function stats(string $state): array
    {
        $total = $this->import->batches->count();

        $completed = $this->import->batches->where('state', $state)->count();

        $progress = $total
            ? round($completed / $total * 100)
            : 0;

        $summary = $this->importBatchRepository
            ->select(
                DB::raw('SUM(json_unquote(json_extract(summary, \'$."created"\'))) AS created'),
                DB::raw('SUM(json_unquote(json_extract(summary, \'$."updated"\'))) AS updated'),
                DB::raw('SUM(json_unquote(json_extract(summary, \'$."deleted"\'))) AS deleted'),
            )
            ->where('import_id', $this->import->id)
            ->where('state', $state)
            ->groupBy('import_id')
            ->first()
            ?->toArray();

        return [
            'batches' => [
                'total' => $total,
                'completed' => $completed,
                'remaining' => $total - $completed,
                'failed' => $this->import->batches
                    ->where('state', self::STATE_PENDING)
                    ->count(),
            ],
            'progress' => $progress,
            'summary' => $summary ?? [
                'created' => 0,
                'updated' => 0,
                'deleted' => 0,
            ],
        ];
    }

    /**
     * All errors grouped by code, summarised for the screen.
     *
     * Only the first few rows of each message and the first few messages are
     * kept; the row-by-row detail is in the downloadable report.
     */
    public function getFormattedErrors(): array
    {
        $errors = [];

        $hiddenMessages = 0;

        foreach ($this->errorHelper->getAllErrorsGroupedByCode() as $groupedErrors) {
            foreach ($groupedErrors as $errorMessage => $rowNumbers) {
                if (count($errors) >= self::ERROR_MESSAGES_PREVIEW) {
                    $hiddenMessages++;

                    continue;
                }

                $errors[] = empty($rowNumbers)
                    ? $errorMessage
                    : $this->summarizeRowNumbers($rowNumbers).': '.$errorMessage;
            }
        }

        if ($hiddenMessages > 0) {
            $errors[] = trans('data_transfer::app.validation.errors.more-issues', [
                'count' => number_format($hiddenMessages),
            ]);
        }

        return $errors;
    }

    /**
     * Uploads error report and save the path to the database.
     */
    public function uploadErrorReport(): ?string
    {
        /**
         * Return null if there are no errors.
         */
        if (! $this->errorHelper->getErrorsCount()) {
            return null;
        }

        /**
         * Return null if there are no invalid rows.
         */
        if (! $this->errorHelper->getInvalidRowsCount()) {
            return null;
        }

        $errors = $this->errorHelper->getAllErrors();

        $path = $this->getTypeImporter()
            ->getSource()
            ->generateErrorReport($errors);

        return $this->relocateErrorReport($path);
    }

    /**
     * Validates source file and returns validation result.
     */
    public function getTypeImporter(): AbstractImporter
    {
        if (! $this->typeImporter) {
            $importerConfig = config('importers.'.$this->import->type);

            $this->typeImporter = app()->make($importerConfig['importer'])
                ->setImport($this->import)
                ->setErrorHelper($this->errorHelper);
        }

        return $this->typeImporter;
    }

    /**
     * Returns number of checked rows.
     */
    public function getProcessedRowsCount(): int
    {
        return $this->getTypeImporter()->getProcessedRowsCount();
    }

    /**
     * Is linking resource required for the import operation.
     */
    public function isLinkingRequired(): bool
    {
        return $this->getTypeImporter()->isLinkingRequired();
    }

    /**
     * Is indexing resource required for the import operation.
     */
    public function isIndexingRequired(): bool
    {
        return $this->getTypeImporter()->isIndexingRequired();
    }

    /**
     * An already-finished image phase, for imports that have no images to fetch.
     */
    protected function noImagesProgress(): array
    {
        return [
            'total' => 0,
            'processed' => 0,
            'progress' => 100,
            'done' => true,
        ];
    }

    /**
     * Validate the whole file in one synchronous pass and shape the result like a
     * finished window, for importers that do not support chunked validation.
     */
    protected function validateWholeFile(): array
    {
        $this->validate();

        $import = $this->getImport();

        $total = (int) $import->processed_rows_count;

        return [
            'processed' => $total,
            'total' => $total,
            'invalid' => (int) $import->invalid_rows_count,
            'done' => true,
            'is_valid' => $this->isValid(),
            'import' => $import->unsetRelations(),
        ];
    }

    /**
     * Finalise the import record from the accumulated window state: re-hydrate
     * the error helper, then write the state, counts, formatted errors and
     * generated error-report path — mirroring the single-pass validate().
     */
    protected function finalizeChunkedValidation(array $state): void
    {
        $this->resetErrorHelper();

        $importer = $this->getTypeImporter()->setSource($this->getSource());

        $counts = $importer->finalizeChunkedErrors($state['error_items'] ?? []);

        $import = $this->importRepository->update([
            'state' => self::STATE_VALIDATED,
            'processed_rows_count' => (int) ($state['offset'] ?? 0),
            'invalid_rows_count' => $counts['invalid_rows_count'],
            'errors_count' => $counts['errors_count'],
            'errors' => $this->getFormattedErrors(),
            'error_file_path' => $this->uploadErrorReport(),
        ], $this->import->id);

        $this->setImport($import);
    }

    /**
     * Swap in a clean error helper before a finalise repopulates it.
     *
     * The last window validates and finalises in one request, so the helper still
     * holds that window's errors and would count them twice.
     */
    protected function resetErrorHelper(): void
    {
        $this->errorHelper = app(Error::class);

        $this->getTypeImporter()->setErrorHelper($this->errorHelper);
    }

    /**
     * Count the source's data rows. A forward parse, no validation.
     */
    protected function countSourceRows(): int
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
     * Number of distinct invalid data rows in an accumulated error list. The
     * null-keyed column errors are not data rows and are excluded.
     */
    protected function countInvalidRows(array $errorItems): int
    {
        return count(array_filter(
            array_keys($errorItems),
            fn ($rowNumber) => is_numeric($rowNumber)
        ));
    }

    /**
     * Resumable validation state, kept beside the import's other generated files
     * so it is cleaned up together with them.
     */
    protected function validationStatePath(): string
    {
        return 'imports/'.$this->import->id.'/processed/validation-state.json';
    }

    /**
     * Read the resumable validation state. Empty on the first window.
     */
    protected function readValidationState(): array
    {
        $disk = Storage::disk('private');

        if (! $disk->exists($this->validationStatePath())) {
            return [];
        }

        return json_decode($disk->get($this->validationStatePath()), true) ?: [];
    }

    /**
     * Persist the resumable validation state for the next window.
     */
    protected function writeValidationState(array $state): void
    {
        Storage::disk('private')->put(
            $this->validationStatePath(),
            json_encode($state, JSON_UNESCAPED_SLASHES)
        );
    }

    /**
     * Remove the resumable validation state once validation has finished.
     */
    protected function clearValidationState(): void
    {
        Storage::disk('private')->delete($this->validationStatePath());
    }

    /**
     * "Row(s) 1, 2, 3 (+9,990 more rows)" — a readable stand-in for a list that
     * could otherwise run to thousands of numbers.
     */
    protected function summarizeRowNumbers(array $rowNumbers): string
    {
        $shown = array_slice($rowNumbers, 0, self::ERROR_ROWS_PREVIEW);

        $summary = 'Row(s) '.implode(', ', $shown);

        $remaining = count($rowNumbers) - count($shown);

        if ($remaining > 0) {
            $summary .= ' '.trans('data_transfer::app.validation.errors.more-rows', [
                'count' => number_format($remaining),
            ]);
        }

        return $summary;
    }

    /**
     * Move the generated error report in beside its import.
     *
     * The source writes it to a flat, timestamped path. Under "imports/{id}"
     * instead, deleting the import takes the report with it.
     */
    protected function relocateErrorReport(?string $path): ?string
    {
        if (! $path) {
            return null;
        }

        $disk = Storage::disk('private');

        /**
         * Never hand back a path that points at nothing — a dangling value is
         * stored on the import and only blows up later, when the download tries
         * to read the file's size.
         */
        if (! $disk->exists($path)) {
            return null;
        }

        $destination = 'imports/'.$this->import->id.'/processed/'.$this->errorReportName($path);

        $disk->delete($destination);

        if ($disk->move($path, $destination)) {
            return $destination;
        }

        /**
         * The private disk is configured with `throw => false`, so a failed move
         * comes back as a silent false. Fall back to a copy, and either way do
         * not leave the flat file loose in imports/.
         */
        if ($disk->copy($path, $destination)) {
            $disk->delete($path);

            return $destination;
        }

        return $disk->exists($path) ? $path : null;
    }

    /**
     * Name the error report after the file it describes, e.g. "products.csv"
     * becomes "products-validation-errors.csv".
     */
    protected function errorReportName(string $generatedPath): string
    {
        $source = basename((string) $this->import->file_path);

        $base = pathinfo($source, PATHINFO_FILENAME) ?: 'import';

        $extension = pathinfo($generatedPath, PATHINFO_EXTENSION)
            ?: (pathinfo($source, PATHINFO_EXTENSION) ?: 'csv');

        return $base.'-validation-errors.'.$extension;
    }
}
