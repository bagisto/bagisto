<?php

namespace Webkul\Marketing\Repositories;

use Illuminate\Support\Facades\Event;
use Webkul\Core\Eloquent\Repository;

class CampaignRepository extends Repository
{
    /**
     * Specify model class name.
     *
     * @return mixed
     */
    public function model()
    {
        return \Webkul\Marketing\Contracts\Campaign::class;
    }

    /**
     * Create.
     *
     * @param  array  $attributes
     * @return mixed
     */
    public function create(array $attributes)
    {
        Event::dispatch('marketing.campaigns.create.before');

        $campaign = parent::create($attributes);

        Event::dispatch('marketing.campaigns.create.after', $campaign);

        return $campaign;
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
        Event::dispatch('marketing.campaigns.update.before', $id);

        $campaign = parent::update($attributes, $id);

        Event::dispatch('marketing.campaigns.update.after', $campaign);

        return $campaign;
    }

    /**
     * Delete.
     *
     * @param  $id
     * @return int
     */
    public function delete($id)
    {
        Event::dispatch('marketing.campaigns.delete.before', $id);

        parent::delete($id);

        Event::dispatch('marketing.campaigns.delete.after', $id);
    }
}
