<?php

namespace Webkul\Tax\Repositories;

use Illuminate\Support\Facades\Event;
use Webkul\Core\Eloquent\Repository;

class TaxCategoryRepository extends Repository
{
    /**
     * Specify model class name.
     *
     * @return string
     */
    public function model()
    {
        return \Webkul\Tax\Contracts\TaxCategory::class;
    }

    /**
     * Create.
     *
     * @param  array  $attributes
     * @return mixed
     */
    public function create(array $attributes)
    {
        Event::dispatch('tax.tax_category.create.before');

        $taxCategory = parent::create($attributes);

        Event::dispatch('tax.tax_category.create.after', $taxCategory);

        return $taxCategory;
    }

    /**
     * Update.
     *
     * @param  array  $attributes
     * @param  $id
     * @return mixed
     */
    public function update(array $attributes, $id)
    {
        Event::dispatch('tax.tax_category.update.before', $id);

        $taxCategory = parent::update($attributes, $id);

        Event::dispatch('tax.tax_category.update.after', $taxCategory);

        return $taxCategory;
    }

    /**
     * Delete.
     *
     * @param  int  $id
     * @return bool
     */
    public function delete($id)
    {
        Event::dispatch('tax.tax_category.delete.before', $id);

        parent::delete($id);

        Event::dispatch('tax.tax_category.delete.after', $id);
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
        $taxCategory->tax_rates;

        $this->model->findOrFail($taxCategory->id)->tax_rates()->sync($data);

        return true;
    }
}
