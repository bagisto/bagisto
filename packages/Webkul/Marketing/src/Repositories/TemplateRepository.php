<?php

namespace Webkul\Marketing\Repositories;

use Illuminate\Support\Facades\Event;
use Webkul\Core\Eloquent\Repository;

class TemplateRepository extends Repository
{
    /**
     * Specify model class name.
     *
     * @return mixed
     */
    public function model()
    {
        return \Webkul\Marketing\Contracts\Template::class;
    }

    /**
     * Create.
     *
     * @param  array  $attributes
     * @return mixed
     */
    public function create(array $attributes)
    {
        Event::dispatch('marketing.templates.create.before');

        $template = parent::create($attributes);

        Event::dispatch('marketing.templates.create.after', $template);

        return $template;
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
        Event::dispatch('marketing.templates.update.before', $id);

        $template = parent::update($attributes, $id);

        Event::dispatch('marketing.templates.update.after', $template);

        return $template;
    }

    /**
     * Delete.
     *
     * @param  int  $id
     * @return int
     */
    public function delete($id)
    {
        Event::dispatch('marketing.templates.delete.before', $id);

        parent::delete($id);

        Event::dispatch('marketing.templates.delete.after', $id);
    }
}
