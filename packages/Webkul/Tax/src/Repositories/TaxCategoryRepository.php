<?php

namespace Webkul\Tax\Repositories;

use Webkul\Core\Eloquent\Repository;

/**
 * Tax Category Reposotory
 *
 * @author    Prashant Singh <prashant.singh852@webkul.com>
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class TaxCategoryRepository extends Repository
{
    /**
     * Specify Model class name
     *
     * @return mixed
     */
    function model()
    {
        return 'Webkul\Tax\Models\TaxCategory';
    }

    public function attachOrDetach($taxCategory, $data) {
        $taxRates = $taxCategory->tax_rates;

        if($taxRates->count() > 0) {
            $taxCategory->tax_rates()->detach();
        }

        foreach($data as $key => $value) {
            $this->model->findOrFail($taxCategory->id)->tax_rates()->attach($taxCategory->id, ['tax_category_id' => $taxCategory->id, 'tax_rate_id' => $value]);
        }

        return true;
    }
}