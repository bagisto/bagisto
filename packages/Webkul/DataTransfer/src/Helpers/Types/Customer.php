<?php

namespace Webkul\DataTransfer\Helpers\Types;

class Customer extends AbstractType
{
    /**
     * Permanent entity columns
     *
     * @var string[]
     */
    protected $permanentAttributes = ['email'];

    /**
     * Save validated batches
     */
    protected function saveValidatedBatches()
    {
        parent::saveValidatedBatches();
    }

    /**
     * Validates row
     * 
     * @return void
     */
    public function validateRow()
    {
    }
}