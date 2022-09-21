<?php

namespace Webkul\Tax\Repositories;

use Webkul\Core\Eloquent\Repository;

class TaxCategoryRepository extends Repository
{
    /**
     * Specify model class name.
     *
     * @return string
     */
    public function model(): string
    {
        return 'Webkul\Tax\Contracts\TaxCategory';
    }

    /**
     * Attach or detach.
     *
     * @param  \Webkul\Tax\Contracts\TaxCategory  $taxCategory
     * @param  array  $data
     * @return bool
     */
    public function attachOrDetach($taxCategory, $data)
    {
        $this->model->findOrFail($taxCategory->id)->tax_rates()->sync($data);

        return true;
    }
}
