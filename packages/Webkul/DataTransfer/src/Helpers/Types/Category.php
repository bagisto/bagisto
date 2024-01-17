<?php

namespace Webkul\DataTransfer\Helpers\Types;

class Category extends AbstractType
{
    /**
     * Permanent entity columns
     *
     * @var string[]
     */
    protected $permanentAttributes = [];

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