<?php

namespace Webkul\DataTransfer\Helpers;

use Webkul\DataTransfer\Contracts\Import as ImportContract;
use Webkul\DataTransfer\Repositories\ImportRepository;
use Webkul\DataTransfer\Helpers\Types\AbstractType;

class Import
{
    public const ACTION_APPEND = 'append';

    public const ACTION_REPLACE = 'replace';

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
        protected Error $errorHelper
    )
    {
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
        $this->errorHelper->initValidationStrategy(
            $this->import->validation_strategy,
            $this->import->allowed_errors
        );

        try {
            $typeImporter = $this->getTypeImporter()->setSource(
                new Source(
                    $this->import->file_path,
                    $this->import->field_separator,
                )
            );

            $typeImporter->validateData();
        } catch (\Exception $e) {
            $this->errorHelper->addError(
                AbstractType::ERROR_CODE_SYSTEM_EXCEPTION,
                null,
                null,
                $e->getMessage()
            );
        }

        if ($this->errorHelper->isErrorLimitExceeded()) {
            return false;
        }

        if ($typeImporter->getProcessedRowsCount() <= $this->errorHelper->getInvalidRowsCount()) {
            return false;
        }

        return true;
    }

    /**
     * Starts import process
     */
    public function start(): bool
    {
        $import = $this->importRepository->update([
            'started_at' => now(),
            'summary'    => [],
        ], $this->import->id);

        $this->setImport($import);

        $typeImporter = $this->getTypeImporter();

        $typeImporter->importData();

        return false;
    }

    /**
     * Start the import process
     */
    public function completed(): void
    {
        $import = $this->importRepository->update([
            'completed_at' => now(),
        ], $this->import->id);

        $this->setImport($import);
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
