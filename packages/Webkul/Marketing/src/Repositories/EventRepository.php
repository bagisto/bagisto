<?php

namespace Webkul\Marketing\Repositories;

use Illuminate\Support\Facades\Event;
use Webkul\Core\Eloquent\Repository;

class EventRepository extends Repository
{
    /**
     * Specify model class name.
     *
     * @return mixed
     */
    public function model()
    {
        return \Webkul\Marketing\Contracts\Event::class;
    }

    /**
     * Save a new entity in repository
     *
     * @param  array  $attributes
     * @return mixed
     */
    public function create(array $attributes)
    {
        Event::dispatch('marketing.events.create.before');

        $event = parent::create($attributes);

        Event::dispatch('marketing.events.create.after', $event);

        return $event;
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
        Event::dispatch('marketing.events.update.before', $id);

        $event = parent::update($attributes, $id);

        Event::dispatch('marketing.events.update.after', $event);

        return $event;
    }

    /**
     * Delete.
     *
     * @param  $id
     * @return int
     */
    public function delete($id)
    {
        Event::dispatch('marketing.events.delete.before', $id);

        parent::delete($id);

        Event::dispatch('marketing.events.delete.after', $id);
    }
}
