<?php

namespace Webkul\DataTransfer\Helpers;

use Webkul\DataTransfer\Contracts\Import as ImportContract;
use Webkul\DataTransfer\Helpers\Types\AbstractType;

class Import
{
    public const ACTION_APPEND = 'append';

    public const ACTION_REPLACE = 'replace';

    public const ACTION_DELETE = 'delete';

    /**
     * Import instance.
     * 
     * @var \Webkul\DataTransfer\Contracts\Import
     */
    protected $import;

    /**
     * Error helper instance.
     * 
     * @var \Webkul\DataTransfer\Helpers\Error
     */
    protected $typeImporter;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(protected Error $errorHelper)
    {
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
    public function validate(ImportContract $import): bool
    {
        $this->import = $import;

        $this->errorHelper->initValidationStrategy(
            $import->validation_strategy,
            $import->allowed_errors
        );

        try {
            $typeImporter = $this->getTypeImporter()->setSource(
                new Source(
                    $import->file_path,
                    $import->field_separator,
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