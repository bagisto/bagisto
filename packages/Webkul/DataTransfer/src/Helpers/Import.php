<?php

namespace Webkul\DataTransfer\Helpers;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Storage;
use Webkul\DataTransfer\Contracts\Import as ImportContract;
use Webkul\DataTransfer\Contracts\ImportBatch as ImportBatchContract;
use Webkul\DataTransfer\Helpers\Types\AbstractType;
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
     * Import state for linking import
     */
    public const STATE_LINKING = 'linking';

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
     * Action constant for replace the resource
     */
    public const ACTION_REPLACE = 'replace';

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
    ) {
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
     */
    public function getImport(): ImportContract
    {
        return $this->import;
    }

    /**
     * Import instance.
     *
     * @return \Webkul\DataTransfer\Helpers\Error
     */
    public function getErrorHelper()
    {
        return $this->errorHelper;
    }

    /**
     * Validates import and returns validation result
     */
    public function validate(): bool
    {
        try {
            $source = new Source(
                $this->import->file_path,
                $this->import->field_separator,
            );

            $typeImporter = $this->getTypeImporter()->setSource($source);

            $typeImporter->validateData();
        } catch (\Exception $e) {
            $this->errorHelper->addError(
                AbstractType::ERROR_CODE_SYSTEM_EXCEPTION,
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
            && $this->import->errors_count > $this->import->allowedErrors
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
                    $errors[] = 'Row(s) ' . implode(', ', $rowNumbers) . ': ' . $errorMessage;
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

        $errorReportPath = 'imports/' . time() . '-error-report.csv';

        $handle = fopen(Storage::disk('private')->path($errorReportPath), 'a');

        $source = $this->getTypeImporter()->getSource();

        $columns = array_merge($source->getColumnNames(), [
            'error',
        ]);

        fputcsv($handle, $columns, $this->import->field_separator);

        $source->rewind();

        $errors = $this->errorHelper->getAllErrors();

        while ($source->valid()) {
            try {
                $rowData = $source->current();
            } catch (\InvalidArgumentException $e) {
                $source->next();

                continue;
            }

            $rowNumber = $source->getCurrentRowNumber();

            $rowErrors = [];

            if (isset($errors[$rowNumber])) {
                $rowErrors = Arr::pluck($errors[$rowNumber], 'message');
            }

            $rowData[] = implode('|', $rowErrors);

            fputcsv($handle, $rowData, $this->import->field_separator);

            $source->next();
        }

        fclose($handle);

        return $errorReportPath;
    }

    /**
     * Validates source file and returns validation result
     */
    public function getTypeImporter(): AbstractType
    {
        if (! $this->typeImporter) {
            $this->typeImporter = app()->make('Webkul\DataTransfer\Helpers\Types\\' . ucfirst($this->import->type))
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
}
