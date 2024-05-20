<?php

namespace Webkul\Tax\Repositories;

use Webkul\Core\Eloquent\Repository;

class TaxCategoryRepository extends Repository
{
    /**
     * Specify model class name.
     */
    public function model(): string
    {
        return 'Webkul\Tax\Contracts\TaxCategory';
    }

    /**
     * Get the configuration options.
     */
    public function getConfigOptions(): array
    {
        $options = [
            [
                'title' => 'admin::app.configuration.index.sales.taxes.categories.none',
                'value' => 0,
            ],
        ];

        foreach ($this->all() as $taxCategory) {
            $options[] = [
                'title' => $taxCategory->name,
                'value' => $taxCategory->id,
            ];
        }

        return $options;
    }
}
