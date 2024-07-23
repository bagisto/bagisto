<?php

namespace Webkul\DataTransfer\Helpers;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Csv;
use PhpOffice\PhpSpreadsheet\Writer\Xls;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Webkul\DataTransfer\Contracts\Import as ImportContract;
use Webkul\DataTransfer\Contracts\ImportBatch as ImportBatchContract;
use Webkul\DataTransfer\Helpers\Importers\AbstractImporter;
use Webkul\DataTransfer\Helpers\Sources\AbstractSource;
use Webkul\DataTransfer\Helpers\Sources\CSV as CSVSource;
use Webkul\DataTransfer\Helpers\Sources\Excel as ExcelSource;
use Webkul\DataTransfer\Repositories\ImportBatchRepository;
use Webkul\DataTransfer\Repositories\ImportRepository;

class Import
{
    /**
     * Import state for pending import
     */
    public const STATE_PENDING = 'pending';

    /**
     * Import state for validated import
     */
    public const STATE_VALIDATED = 'validated';

    /**
     * Import state for processing import
     */
    public const STATE_PROCESSING = 'processing';

    /**
     * Import state for processed import
     */
    public const STATE_PROCESSED = 'processed';

    /**
     * Import state for linking import
     */
    public const STATE_LINKING = 'linking';

    /**
     * Import state for linked import
     */
    public const STATE_LINKED = 'linked';

    /**
     * Import state for indexing import
     */
    public const STATE_INDEXING = 'indexing';

    /**
     * Import state for indexed import
     */
    public const STATE_INDEXED = 'indexed';

    /**
     * Import state for completed import
     */
    public const STATE_COMPLETED = 'completed';

    /**
     * Validation strategy for skipping the error during the import process
     */
    public const VALIDATION_STRATEGY_SKIP_ERRORS = 'skip-errors';

    /**
     * Validation strategy for stopping the import process on error
     */
    public const VALIDATION_STRATEGY_STOP_ON_ERROR = 'stop-on-errors';

    /**
     * Action constant for updating/creating for the resource
     */
    public const ACTION_APPEND = 'append';

    /**
     * Action constant for deleting the resource
     */
    public const ACTION_DELETE = 'delete';

    /**
     * Import instance.
     */
    protected ImportContract $import;

    /**
     * Error helper instance.
     *
     * @var \Webkul\DataTransfer\Helpers\Error
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
     * @return \Webkul\DataTransfer\Helpers\Error
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
        if (Str::contains($this->import->file_path, '.csv')) {
            $source = new CSVSource(
                $this->import->file_path,
                $this->import->field_separator,
            );
        } else {
            $source = new ExcelSource(
                $this->import->file_path,
                $this->import->field_separator,
            );
        }

        return $source;
    }

    /**
     * Validates import and returns validation result
     */
    public function validate(): bool
    {
        try {
            $source = $this->getSource();

            $typeImporter = $this->getTypeImporter()->setSource($source);

            $typeImporter->validateData();
        } catch (\Exception $e) {
            $this->errorHelper->addError(
                AbstractImporter::ERROR_CODE_SYSTEM_EXCEPTION,
                null,
                null,
                $e->getMessage()
            );
        }

        $import = $this->importRepository->update([
            'state'                => self::STATE_VALIDATED,
            'processed_rows_count' => $this->getProcessedRowsCount(),
            'invalid_rows_count'   => $this->errorHelper->getInvalidRowsCount(),
            'errors_count'         => $this->errorHelper->getErrorsCount(),
            'errors'               => $this->getFormattedErrors(),
            'error_file_path'      => $this->uploadErrorReport(),
        ], $this->import->id);

        $this->setImport($import);

        return $this->isValid();
    }

    /**
     * Starts import process
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
     * Check if error limit has been exceeded
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
     * Starts import process
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
     * Link import resources
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
     * Index import resources
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
     * Started the import process
     */
    public function started(): void
    {
        $import = $this->importRepository->update([
            'state'      => self::STATE_PROCESSING,
            'started_at' => now(),
            'summary'    => [],
        ], $this->import->id);

        $this->setImport($import);

        Event::dispatch('data_transfer.imports.started', $import);
    }

    /**
     * Started the import linking process
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
     * Started the import indexing process
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
     * Start the import process
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
            'state'        => self::STATE_COMPLETED,
            'summary'      => $summary,
            'completed_at' => now(),
        ], $this->import->id);

        $this->setImport($import);

        Event::dispatch('data_transfer.imports.completed', $import);
    }

    /**
     * Returns import stats
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
            'batches'  => [
                'total'     => $total,
                'completed' => $completed,
                'remaining' => $total - $completed,
            ],
            'progress' => $progress,
            'summary'  => $summary ?? [
                'created' => 0,
                'updated' => 0,
                'deleted' => 0,
            ],
        ];
    }

    /**
     * Return all error grouped by error code
     */
    public function getFormattedErrors(): array
    {
        $errors = [];

        foreach ($this->errorHelper->getAllErrorsGroupedByCode() as $groupedErrors) {
            foreach ($groupedErrors as $errorMessage => $rowNumbers) {
                if (! empty($rowNumbers)) {
                    $errors[] = 'Row(s) '.implode(', ', $rowNumbers).': '.$errorMessage;
                } else {
                    $errors[] = $errorMessage;
                }
            }
        }

        return $errors;
    }

    /**
     * Uploads error report and save the path to the database
     */
    public function uploadErrorReport(): ?string
    {
        /**
         * Return null if there are no errors
         */
        if (! $this->errorHelper->getErrorsCount()) {
            return null;
        }

        /**
         * Return null if there are no invalid rows
         */
        if (! $this->errorHelper->getInvalidRowsCount()) {
            return null;
        }

        $errors = $this->errorHelper->getAllErrors();

        $source = $this->getTypeImporter()->getSource();

        $source->rewind();

        $spreadsheet = new Spreadsheet();

        $sheet = $spreadsheet->getActiveSheet();

        /**
         * Add headers with extra error column
         */
        $sheet->fromArray(
            [array_merge($source->getColumnNames(), [
                'error',
            ])],
            null,
            'A1'
        );

        $rowNumber = 2;

        while ($source->valid()) {
            try {
                $rowData = $source->current();
            } catch (\InvalidArgumentException $e) {
                $source->next();

                continue;
            }

            $rowErrors = $errors[$source->getCurrentRowNumber()] ?? [];

            if (! empty($rowErrors)) {
                $rowErrors = Arr::pluck($rowErrors, 'message');
            }

            $rowData[] = implode('|', $rowErrors);

            $sheet->fromArray([$rowData], null, 'A'.$rowNumber++);

            $source->next();
        }

        $fileType = pathinfo($this->import->file_path, PATHINFO_EXTENSION);

        switch ($fileType) {
            case 'csv':
                $writer = new Csv($spreadsheet);

                $writer->setDelimiter($this->import->field_separator);

                break;

            case 'xls':
                $writer = new Xls($spreadsheet);

            case 'xlsx':
                $writer = new Xlsx($spreadsheet);

                break;

            default:
                throw new \InvalidArgumentException("Unsupported file type: $fileType");
        }

        $errorFilePath = 'imports/'.time().'-error-report.'.$fileType;

        $writer->save(Storage::disk('private')->path($errorFilePath));

        return $errorFilePath;
    }

    /**
     * Validates source file and returns validation result
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
     * Is linking resource required for the import operation
     */
    public function isLinkingRequired(): bool
    {
        return $this->getTypeImporter()->isLinkingRequired();
    }

    /**
     * Is indexing resource required for the import operation
     */
    public function isIndexingRequired(): bool
    {
        return $this->getTypeImporter()->isIndexingRequired();
    }
}
