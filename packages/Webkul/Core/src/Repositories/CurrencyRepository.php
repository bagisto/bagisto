<?php

namespace Webkul\Core\Repositories;

use Illuminate\Support\Facades\Event;
use Webkul\Core\Eloquent\Repository;
use Webkul\Core\Contracts\Currency;

class CurrencyRepository extends Repository
{
    /**
     * Specify model class name.
     *
     * @return string
     */
    public function model(): string
    {
        return Currency::class;
    }

    /**
     * Create.
     *
     * @param  array  $attributes
     * @return mixed
     */
    public function create(array $attributes)
    {
        Event::dispatch('core.currency.create.before');

        $currency = parent::create($attributes);

        Event::dispatch('core.currency.create.after', $currency);

        return $currency;
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
        Event::dispatch('core.currency.update.before', $id);

        $currency = parent::update($attributes, $id);

        Event::dispatch('core.currency.update.after', $currency);

        return $currency;
    }

    /**
     * Delete.
     *
     * @param  int  $id
     * @return bool
     */
    public function delete($id)
    {
        Event::dispatch('core.currency.delete.before', $id);

        if ($this->model->count() == 1) {
            return false;
        }

        if ($this->model->destroy($id)) {
            Event::dispatch('core.currency.delete.after', $id);

            return true;
        }

        return false;
    }
}
