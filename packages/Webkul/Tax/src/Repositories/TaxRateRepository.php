<?php

namespace Webkul\Tax\Repositories;

use Illuminate\Support\Facades\Event;
use Webkul\Core\Eloquent\Repository;

class TaxRateRepository extends Repository
{
    /**
     * Specify model class name.
     *
     * @return mixed
     */
    public function model()
    {
        return \Webkul\Tax\Contracts\TaxRate::class;
    }

    /**
     * Create.
     *
     * @param  array  $attributes
     * @return mixed
     */
    public function create(array $attributes)
    {
        Event::dispatch('tax.tax_rate.create.before');

        $taxRate = parent::create($attributes);

        Event::dispatch('tax.tax_rate.create.after', $taxRate);

        return $taxRate;
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
        Event::dispatch('tax.tax_rate.update.before', $id);

        $taxRate = parent::update($attributes, $id);

        Event::dispatch('tax.tax_rate.update.after', $taxRate);

        return $taxRate;
    }

    /**
     * Delete.
     *
     * @param  int  $id
     * @return bool
     */
    public function delete($id)
    {
        Event::dispatch('tax.tax_rate.delete.before', $id);

        parent::delete($id);

        Event::dispatch('tax.tax_rate.delete.after', $id);
    }
}
