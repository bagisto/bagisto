<?php

namespace Webkul\DataTransfer\Helpers\Importers\Customer;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Validator;
use Webkul\Core\Rules\PhoneNumber;
use Webkul\Customer\Repositories\CustomerGroupRepository;
use Webkul\Customer\Repositories\CustomerRepository;
use Webkul\DataTransfer\Contracts\ImportBatch as ImportBatchContract;
use Webkul\DataTransfer\Helpers\Import;
use Webkul\DataTransfer\Helpers\Importers\AbstractImporter;
use Webkul\DataTransfer\Repositories\ImportBatchRepository;

class Importer extends AbstractImporter
{
    /**
     * Error code for non existing email.
     *
     * @var string
     */
    const ERROR_EMAIL_NOT_FOUND_FOR_DELETE = 'email_not_found_to_delete';

    /**
     * Error code for duplicated email.
     *
     * @var string
     */
    const ERROR_DUPLICATE_EMAIL = 'duplicated_email';

    /**
     * Error code for duplicated phone.
     *
     * @var string
     */
    const ERROR_DUPLICATE_PHONE = 'duplicated_phone';

    /**
     * Error code for invalid attribute family code.
     *
     * @var string
     */
    const ERROR_INVALID_CUSTOMER_GROUP_CODE = 'customer_group_code_not_found';

    /**
     * Permanent entity columns.
     *
     * @var string[]
     */
    protected array $validColumnNames = [
        'email',
        'customer_group_code',
        'first_name',
        'last_name',
        'phone',
        'gender',
        'date_of_birth',
    ];

    /**
     * Error message templates.
     *
     * @var string[]
     */
    protected array $messages = [
        self::ERROR_EMAIL_NOT_FOUND_FOR_DELETE  => 'data_transfer::app.importers.customers.validation.errors.email-not-found',
        self::ERROR_DUPLICATE_EMAIL             => 'data_transfer::app.importers.customers.validation.errors.duplicate-email',
        self::ERROR_DUPLICATE_PHONE             => 'data_transfer::app.importers.customers.validation.errors.duplicate-phone',
        self::ERROR_INVALID_CUSTOMER_GROUP_CODE => 'data_transfer::app.importers.customers.validation.errors.invalid-customer-group',
    ];

    /**
     * Permanent entity columns.
     *
     * @var string[]
     */
    protected $permanentAttributes = ['email'];

    /**
     * Permanent entity column.
     */
    protected string $masterAttributeCode = 'email';

    /**
     * Cached customer groups.
     */
    protected mixed $customerGroups = [];

    /**
     * Emails storage.
     */
    protected array $emails = [];

    /**
     * Phones storage.
     */
    protected array $phones = [];

    /**
     * Create a new helper instance.
     *
     * @return void
     */
    public function __construct(
        protected ImportBatchRepository $importBatchRepository,
        protected CustomerRepository $customerRepository,
        protected CustomerGroupRepository $customerGroupRepository,
        protected Storage $customerStorage
    ) {
        $this->initCustomerGroups();

        parent::__construct($importBatchRepository);
    }

    /**
     * Load all attributes and families to use later.
     */
    protected function initCustomerGroups(): void
    {
        $this->customerGroups = $this->customerGroupRepository->all();
    }

    /**
     * Initialize Product error templates.
     */
    protected function initErrorMessages(): void
    {
        foreach ($this->messages as $errorCode => $message) {
            $this->errorHelper->addErrorMessage($errorCode, trans($message));
        }

        parent::initErrorMessages();
    }

    /**
     * Validate data.
     */
    public function validateData(): void
    {
        $this->customerStorage->init();

        parent::validateData();
    }

    /**
     * Validates row.
     */
    public function validateRow(array $rowData, int $rowNumber): bool
    {
        /**
         * If row is already validated than no need for further validation.
         */
        if (isset($this->validatedRows[$rowNumber])) {
            return ! $this->errorHelper->isRowInvalid($rowNumber);
        }

        $this->validatedRows[$rowNumber] = true;

        /**
         * If import action is delete than no need for further validation.
         */
        if ($this->import->action == Import::ACTION_DELETE) {
            if (! $this->isEmailExist($rowData['email'])) {
                $this->skipRow($rowNumber, self::ERROR_EMAIL_NOT_FOUND_FOR_DELETE);

                return false;
            }

            return true;
        }

        /**
         * Check if customer group code exists.
         */
        if (! $this->customerGroups->where('code', $rowData['customer_group_code'])->first()) {
            $this->skipRow($rowNumber, self::ERROR_INVALID_CUSTOMER_GROUP_CODE, 'customer_group_code');

            return false;
        }

        /**
         * Validate product attributes.
         */
        $validator = Validator::make($rowData, [
            'customer_group_code' => 'required',
            'first_name'          => 'required|string',
            'last_name'           => 'required|string',
            'gender'              => 'required:in,Male,Female,Other',
            'email'               => 'required|email',
            'date_of_birth'       => 'date|before:today',
            'phone'               => ! empty($rowData['phone']) ? new PhoneNumber : '',
        ]);

        if ($validator->fails()) {
            $failedAttributes = $validator->failed();

            foreach ($validator->errors()->getMessages() as $attributeCode => $message) {
                $errorCode = array_key_first($failedAttributes[$attributeCode] ?? []);

                $this->skipRow($rowNumber, $errorCode, $attributeCode, current($message));
            }
        }

        /**
         * Check if email is unique.
         */
        if (! in_array($rowData['email'], $this->emails)) {
            $this->emails[] = $rowData['email'];
        } else {
            $message = sprintf(
                trans($this->messages[self::ERROR_DUPLICATE_EMAIL]),
                $rowData['email']
            );

            $this->skipRow($rowNumber, self::ERROR_DUPLICATE_EMAIL, 'email', $message);
        }

        /**
         * Check if phone is unique.
         */
        if (! in_array($rowData['phone'], $this->phones)) {
            if (! empty($rowData['phone'])) {
                $this->phones[] = $rowData['phone'];
            }
        } else {
            $message = sprintf(
                trans($this->messages[self::ERROR_DUPLICATE_PHONE]),
                $rowData['phone']
            );

            $this->skipRow($rowNumber, self::ERROR_DUPLICATE_PHONE, 'phone', $message);
        }

        return ! $this->errorHelper->isRowInvalid($rowNumber);
    }

    /**
     * Start the import process.
     */
    public function importBatch(ImportBatchContract $batch): bool
    {
        Event::dispatch('data_transfer.imports.batch.import.before', $batch);

        if ($batch->import->action == Import::ACTION_DELETE) {
            $this->deleteCustomers($batch);
        } else {
            $this->saveCustomersData($batch);
        }

        /**
         * Update import batch summary.
         */
        $batch = $this->importBatchRepository->update([
            'state' => Import::STATE_PROCESSED,

            'summary'      => [
                'created' => $this->getCreatedItemsCount(),
                'updated' => $this->getUpdatedItemsCount(),
                'deleted' => $this->getDeletedItemsCount(),
            ],
        ], $batch->id);

        Event::dispatch('data_transfer.imports.batch.import.after', $batch);

        return true;
    }

    /**
     * Delete customers from current batch.
     */
    protected function deleteCustomers(ImportBatchContract $batch): bool
    {
        /**
         * Load customer storage with batch emails.
         */
        $this->customerStorage->load(Arr::pluck($batch->data, 'email'));

        $idsToDelete = [];

        foreach ($batch->data as $rowData) {
            if (! $this->isEmailExist($rowData['email'])) {
                continue;
            }

            $idsToDelete[] = $this->customerStorage->get($rowData['email']);
        }

        $idsToDelete = array_unique($idsToDelete);

        $this->deletedItemsCount = count($idsToDelete);

        $this->customerRepository->deleteWhere([['id', 'IN', $idsToDelete]]);

        return true;
    }

    /**
     * Save customers from current batch.
     */
    protected function saveCustomersData(ImportBatchContract $batch): bool
    {
        /**
         * Load customer storage with batch email.
         */
        $this->customerStorage->load(Arr::pluck($batch->data, 'email'));

        $customers = [];

        foreach ($batch->data as $rowData) {
            /**
             * Prepare customers for import
             */
            $this->prepareCustomers($rowData, $customers);
        }

        $this->saveCustomers($customers);

        return true;
    }

    /**
     * Prepare customers from current batch.
     */
    public function prepareCustomers(array $rowData, array &$customers): void
    {
        $customerGroupId = $this->customerGroups
            ->where('code', $rowData['customer_group_code'])
            ->first()->id;

        $attributes = Arr::except($rowData, ['customer_group_code']);

        if ($this->isEmailExist($rowData['email'])) {
            $customers['update'][$rowData['email']] = array_merge($attributes, [
                'customer_group_id' => $customerGroupId,
            ]);
        } else {
            $customers['insert'][$rowData['email']] = array_merge($attributes, [
                'customer_group_id' => $customerGroupId,
                'created_at'        => $rowData['created_at'] ?? now(),
                'updated_at'        => $rowData['updated_at'] ?? now(),
            ]);
        }
    }

    /**
     * Save customers from current batch.
     */
    public function saveCustomers(array $customers): void
    {
        if (! empty($customers['update'])) {
            $this->updatedItemsCount += count($customers['update']);

            $this->customerRepository->upsert(
                $customers['update'],
                $this->masterAttributeCode
            );
        }

        if (! empty($customers['insert'])) {
            $this->createdItemsCount += count($customers['insert']);

            $this->customerRepository->insert($customers['insert']);
        }
    }

    /**
     * Check if email exists.
     */
    public function isEmailExist(string $email): bool
    {
        return $this->customerStorage->has($email);
    }
}
