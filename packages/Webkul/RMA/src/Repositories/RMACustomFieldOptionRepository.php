<?php

namespace Webkul\RMA\Repositories;

use Webkul\Core\Eloquent\Repository;
use Webkul\RMA\Contracts\RMACustomFieldOption;

class RMACustomFieldOptionRepository extends Repository
{
    /**
     * Specify model class name.
     */
    public function model(): string
    {
        return RMACustomFieldOption::class;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function createOption(array $dataOptions, int $rmaCustomFieldId): void
    {
        foreach ($dataOptions['options'] as $key => $option) {
            $this->model->create([
                'rma_custom_field_id' => $rmaCustomFieldId,
                'name'                => $option,
                'value'               => $dataOptions['value'][$key],
            ]);
        }
    }
}
