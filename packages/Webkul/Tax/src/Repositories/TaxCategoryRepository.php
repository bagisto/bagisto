<?php

namespace Webkul\Tax\Repositories;

use Webkul\Core\Eloquent\Repository;

class TaxCategoryRepository extends Repository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    function model()
    {
        return 'Webkul\Tax\Contracts\TaxCategory';
    }

    /**
     * @param  \Webkul\Tax\Contracts\TaxCategory  $taxCategory
     * @param  array  $data
     * @return bool
     */
    public function attachOrDetach($taxCategory, $data)
    {
        $taxRates = $taxCategory->tax_rates;

        $this->model->findOrFail($taxCategory->id)->tax_rates()->sync($data);

        return true;
    }
}