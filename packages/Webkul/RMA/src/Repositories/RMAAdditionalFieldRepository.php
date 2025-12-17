<?php

namespace Webkul\RMA\Repositories;

use Webkul\Core\Eloquent\Repository;
use Webkul\RMA\Contracts\RMAAdditionalField;

class RMAAdditionalFieldRepository extends Repository
{
    /**
     * Specify model class name.
     */
    public function model(): string
    {
        return RMAAdditionalField::class;
    }

    /**
     * Create many additional fields for RMA.
     */
    public function createManyForRma(int $rmaId, array $customAttributes): void
    {
        foreach ($customAttributes as $key => $customAttribute) {
            $customAttributesData = [
                'rma_id' => $rmaId,
                'name'   => $key,
                'value'  => is_array($customAttribute) ? implode(',', $customAttribute) : $customAttribute,
            ];

            $this->create($customAttributesData);
        }
    }
}
