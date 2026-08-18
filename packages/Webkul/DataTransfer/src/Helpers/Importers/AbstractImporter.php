<?php

namespace Webkul\DataTransfer\Helpers\Importers;

use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Event;
use Webkul\DataTransfer\Contracts\Import as ImportContract;
use Webkul\DataTransfer\Contracts\ImportBatch as ImportBatchContract;
use Webkul\DataTransfer\Helpers\Error;
use Webkul\DataTransfer\Helpers\Import;
use Webkul\DataTransfer\Helpers\Importers\Concerns\ValidatesInChunks;
use Webkul\DataTransfer\Helpers\Source;
use Webkul\DataTransfer\Jobs\Import\Completed as CompletedJob;
use Webkul\DataTransfer\Jobs\Import\ImportBatch as ImportBatchJob;
use Webkul\DataTransfer\Jobs\Import\IndexBatch as IndexBatchJob;
use Webkul\DataTransfer\Jobs\Import\Indexing as IndexingJob;
use Webkul\DataTransfer\Jobs\Import\LinkBatch as LinkBatchJob;
use Webkul\DataTransfer\Jobs\Import\Linking as LinkingJob;
use Webkul\DataTransfer\Repositories\ImportBatchRepository;

abstract class AbstractImporter
{
    use ValidatesInChunks;

    /**
     * Error code for system exception.
     */
    public const ERROR_CODE_SYSTEM_EXCEPTION = 'system_exception';

    /**
     * Error code for column not found.
     */
    public const ERROR_CODE_COLUMN_NOT_FOUND = 'column_not_found';

    /**
     * Error code for column empty header.
     */
    public const ERROR_CODE_COLUMN_EMPTY_HEADER = 'column_empty_header';

    /**
     * Error code for column name invalid.
     */
    public const ERROR_CODE_COLUMN_NAME_INVALID = 'column_name_invalid';

    /**
     * Error code for invalid attribute.
     */
    public const ERROR_CODE_INVALID_ATTRIBUTE = 'invalid_attribute_name';

    /**
     * Error code for wrong quotes.
     */
    public const ERROR_CODE_WRONG_QUOTES = 'wrong_quotes';

    /**
     * Error code for wrong columns number.
     */
    public const ERROR_CODE_COLUMNS_NUMBER = 'wrong_columns_number';

    /**
     * Error message templates.
     */
    protected array $errorMessages = [
        self::ERROR_CODE_SYSTEM_EXCEPTION => 'data_transfer::app.validation.errors.system',
        self::ERROR_CODE_COLUMN_NOT_FOUND => 'data_transfer::app.validation.errors.column-not-found',
        self::ERROR_CODE_COLUMN_EMPTY_HEADER => 'data_transfer::app.validation.errors.column-empty-headers',
        self::ERROR_CODE_COLUMN_NAME_INVALID => 'data_transfer::app.validation.errors.column-name-invalid',
        self::ERROR_CODE_INVALID_ATTRIBUTE => 'data_transfer::app.validation.errors.invalid-attribute',
        self::ERROR_CODE_WRONG_QUOTES => 'data_transfer::app.validation.errors.wrong-quotes',
        self::ERROR_CODE_COLUMNS_NUMBER => 'data_transfer::app.validation.errors.column-numbers',
    ];

    public const BATCH_SIZE = 100;

    /**
     * Is linking required
     */
    protected bool $linkingRequired = false;

    /**
     * Is indexing required
     */
    protected bool $indexingRequired = false;

    /**
     * Error helper instance.
     *
     * @var Error
     */
    protected $errorHelper;

    /**
     * Import instance.
     */
    protected ImportContract $import;

    /**
     * Source instance.
     *
     * @var Source
     */
    protected $source;

    /**
     * Valid column names
     */
    protected array $validColumnNames = [];

    /**
     * Array of numbers of validated rows as keys and boolean TRUE as values
     */
    protected array $validatedRows = [];

    /**
     * Number of rows processed by validation
     */
    protected int $processedRowsCount = 0;

    /**
     * Number of created items
     */
    protected int $createdItemsCount = 0;

    /**
     * Number of updated items
     */
    protected int $updatedItemsCount = 0;

    /**
     * Number of deleted items
     */
    protected int $deletedItemsCount = 0;

    /**
     * Create a new helper instance.
     *
     * @return void
     */
    public function __construct(protected ImportBatchRepository $importBatchRepository) {}

    /**
     * Validate data row
     */
    abstract public function validateRow(array $rowData, int $rowNumber): bool;

    /**
     * Import data rows
     */
    abstract public function importBatch(ImportBatchContract $importBatchContract): bool;

    /**
     * Initialize Product error messages
     */
    protected function initErrorMessages(): void
    {
        foreach ($this->errorMessages as $errorCode => $message) {
            $this->errorHelper->addErrorMessage($errorCode, trans($message));
        }
    }

    /**
     * Import instance.
     */
    public function setImport(ImportContract $import): self
    {
        $this->import = $import;

        return $this;
    }

    /**
     * Import instance.
     *
     * @param  Source  $source
     */
    public function setSource($source)
    {
        $this->source = $source;

        return $this;
    }

    /**
     * Import instance.
     *
     * @param  Error  $errorHelper
     */
    public function setErrorHelper($errorHelper): self
    {
        $this->errorHelper = $errorHelper;

        $this->initErrorMessages();

        return $this;
    }

    /**
     * Import instance.
     *
     * @return Source
     */
    public function getSource()
    {
        return $this->source;
    }

    /**
     * Retrieve valid column names
     */
    public function getValidColumnNames(): array
    {
        return $this->validColumnNames;
    }

    /**
     * Validate data.
     */
    public function validateData(): void
    {
        Event::dispatch('data_transfer.imports.validate.before', $this->import);

        $this->prepareForValidation();

        $this->validateColumns();

        if (! $this->errorHelper->getErrorsCount()) {
            $this->saveValidatedBatches();
        }

        Event::dispatch('data_transfer.imports.validate.after', $this->import);
    }

    /**
     * Load whatever must be in place before any row is validated — the storage of
     * existing records rows are checked against, typically.
     *
     * Called once per validation pass on every path: at the top of validateData(),
     * and at the start of each window on the chunked and queued paths, since every
     * window runs in a fresh request or job with nothing loaded.
     */
    protected function prepareForValidation(): void {}

    /**
     * Validate the source's columns against the importer's permanent and valid
     * column names.
     *
     * Split out of validateData() so the chunked and queued paths can run it
     * once, up-front, without reading a single data row: a bad header aborts the
     * whole validation, and there is no point dispatching row windows for a file
     * that cannot be read.
     */
    protected function validateColumns(): void
    {
        $errors = [];

        $absentColumns = array_diff($this->permanentAttributes, $this->getSource()->getColumnNames());

        if (! empty($absentColumns)) {
            $errors[self::ERROR_CODE_COLUMN_NOT_FOUND] = $absentColumns;
        }

        foreach ($this->getSource()->getColumnNames() as $columnNumber => $columnName) {
            if (empty($columnName)) {
                $errors[self::ERROR_CODE_COLUMN_EMPTY_HEADER][] = $columnNumber + 1;
            } elseif (! preg_match('/^[a-z][a-z0-9_]*$/', $columnName)) {
                $errors[self::ERROR_CODE_COLUMN_NAME_INVALID][] = $columnName;
            } elseif (! in_array($columnName, $this->getValidColumnNames())) {
                $errors[self::ERROR_CODE_INVALID_ATTRIBUTE][] = $columnName;
            }
        }

        /**
         * Add Columns Errors
         */
        foreach ($errors as $errorCode => $error) {
            $this->addErrors($errorCode, $error);
        }
    }

    /**
     * Save validated batches
     */
    protected function saveValidatedBatches(): self
    {
        $source = $this->getSource();

        $batchRows = [];

        $source->rewind();

        /**
         * Clean previous saved batches
         */
        $this->importBatchRepository->deleteWhere([
            'import_id' => $this->import->id,
        ]);

        while (
            $source->valid()
            || count($batchRows)
        ) {
            if (
                count($batchRows) == self::BATCH_SIZE
                || ! $source->valid()
            ) {
                $this->importBatchRepository->create([
                    'import_id' => $this->import->id,
                    'data' => $batchRows,
                ]);

                $batchRows = [];
            }

            if ($source->valid()) {
                $rowData = $source->current();

                if ($this->validateRow($rowData, $source->getCurrentRowNumber())) {
                    $batchRows[] = $this->prepareRowForDb($rowData);
                }

                $this->processedRowsCount++;

                $source->next();
            }
        }

        return $this;
    }

    /**
     * Start the import process
     */
    public function importData(?ImportBatchContract $importBatch = null): bool
    {
        if ($importBatch) {
            $this->importBatch($importBatch);

            return true;
        }

        $typeBatches = [];

        foreach ($this->import->batches as $batch) {
            $typeBatches['import'][] = new ImportBatchJob($batch);

            if ($this->isLinkingRequired()) {
                $typeBatches['link'][] = new LinkBatchJob($batch);
            }

            if ($this->isIndexingRequired()) {
                $typeBatches['index'][] = new IndexBatchJob($batch);
            }
        }

        /**
         * Failures are allowed through so that one bad batch cannot cancel the
         * rest of its own batch and, with it, the remaining links in the chain.
         * A cancelled chain leaves the import stuck in `processing` for good,
         * with a progress bar that never reaches the end; letting it run on
         * imports everything that can be imported and reports what could not.
         */
        $chain[] = Bus::batch($typeBatches['import'])->allowFailures();

        if (! empty($typeBatches['link'])) {
            $chain[] = new LinkingJob($this->import);

            $chain[] = Bus::batch($typeBatches['link'])->allowFailures();
        }

        if (! empty($typeBatches['index'])) {
            $chain[] = new IndexingJob($this->import);

            $chain[] = Bus::batch($typeBatches['index'])->allowFailures();
        }

        $chain[] = new CompletedJob($this->import);

        Bus::chain($chain)->dispatch();

        return true;
    }

    /**
     * Link resource data.
     */
    public function linkData(ImportBatchContract $importBatch): bool
    {
        $this->linkBatch($importBatch);

        return true;
    }

    /**
     * Index resource data.
     */
    public function indexData(ImportBatchContract $importBatch): bool
    {
        $this->indexBatch($importBatch);

        return true;
    }

    /**
     * Add errors to error aggregator
     */
    protected function addErrors(string $code, mixed $errors): void
    {
        $this->errorHelper->addError(
            $code,
            null,
            implode('", "', $errors)
        );
    }

    /**
     * Add row as skipped
     *
     * @param  int|null  $rowNumber
     * @param  string|null  $columnName
     * @param  string|null  $errorMessage
     * @return $this
     */
    protected function skipRow($rowNumber, string $errorCode, $columnName = null, $errorMessage = null): self
    {
        $this->errorHelper->addError(
            $errorCode,
            $rowNumber,
            $columnName,
            $errorMessage
        );

        $this->errorHelper->addRowToSkip($rowNumber);

        return $this;
    }

    /**
     * Prepare row data to save into the database
     */
    protected function prepareRowForDb(array $rowData): array
    {
        $rowData = array_map(function ($value) {
            return $value === '' ? null : $value;
        }, $rowData);

        return $rowData;
    }

    /**
     * Returns number of checked rows
     */
    public function getProcessedRowsCount(): int
    {
        return $this->processedRowsCount;
    }

    /**
     * Drop everything accumulated while processing a batch.
     *
     * A queue worker keeps its container alive between jobs, so per-batch state
     * left on the importer is carried into the next batch — it grows for the
     * lifetime of the worker rather than the lifetime of a batch. Importers that
     * cache more (loaded attributes, sku maps) extend this.
     */
    public function releaseBatchMemory(): void
    {
        $this->validatedRows = [];
    }

    /**
     * Zero the created/updated/deleted tallies.
     *
     * They are written into the batch summary at the end of importBatch(), so a
     * batch that is attempted more than once has to start counting from zero
     * again — otherwise the second attempt reports the first attempt's rows on
     * top of its own.
     */
    public function resetItemCounts(): void
    {
        $this->createdItemsCount = 0;

        $this->updatedItemsCount = 0;

        $this->deletedItemsCount = 0;
    }

    /**
     * Returns number of created items count
     */
    public function getCreatedItemsCount(): int
    {
        return $this->createdItemsCount;
    }

    /**
     * Returns number of updated items count
     */
    public function getUpdatedItemsCount(): int
    {
        return $this->updatedItemsCount;
    }

    /**
     * Returns number of deleted items count
     */
    public function getDeletedItemsCount(): int
    {
        return $this->deletedItemsCount;
    }

    /**
     * Is linking resource required for the import operation
     */
    public function isLinkingRequired(): bool
    {
        if ($this->import->action == Import::ACTION_DELETE) {
            return false;
        }

        return $this->linkingRequired;
    }

    /**
     * Is indexing resource required for the import operation
     */
    public function isIndexingRequired(): bool
    {
        if ($this->import->action == Import::ACTION_DELETE) {
            return false;
        }

        return $this->indexingRequired;
    }
}
