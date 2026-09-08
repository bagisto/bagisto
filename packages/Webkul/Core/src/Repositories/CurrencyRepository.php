<?php

namespace Webkul\Core\Repositories;

use Illuminate\Support\Facades\Event;
use Webkul\Core\Contracts\Currency;
use Webkul\Core\Eloquent\Repository;

class CurrencyRepository extends Repository
{
    /**
     * Specify model class name.
     */
    public function model(): string
    {
        return Currency::class;
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

        if (parent::delete($id)) {
            Event::dispatch('core.currency.delete.after', $id);

            return true;
        }

        return false;
    }
}
