<?php

namespace Webkul\Core\Repositories;

use Illuminate\Support\Facades\Event;
use Prettus\Repository\Traits\CacheableRepository;
use Webkul\Core\Eloquent\Repository;

class ExchangeRateRepository extends Repository
{
    use CacheableRepository;

    /**
     * Specify model class name.
     *
     * @return mixed
     */
    public function model()
    {
        return \Webkul\Core\Contracts\CurrencyExchangeRate::class;
    }

    /**
     * Create.
     *
     * @param  array  $attributes
     * @return mixed
     */
    public function create(array $attributes)
    {
        Event::dispatch('core.exchange_rate.create.before');

        $exchangeRate = parent::create($attributes);

        Event::dispatch('core.exchange_rate.create.after', $exchangeRate);

        return $exchangeRate;
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
        Event::dispatch('core.exchange_rate.update.before', $id);

        $exchangeRate = parent::update($attributes, $id);

        Event::dispatch('core.exchange_rate.update.after', $exchangeRate);

        return $exchangeRate;
    }

    /**
     * Delete.
     *
     * @param  int  $id
     * @return bool
     */
    public function delete($id)
    {
        Event::dispatch('core.exchange_rate.delete.before', $id);

        parent::delete($id);

        Event::dispatch('core.exchange_rate.delete.after', $id);
    }
}
