<?php

namespace Webkul\Core;

use Illuminate\Database\Eloquent\Model;
use Shetabit\Visitor\Visitor as BaseVisitor;
use Webkul\Core\Jobs\UpdateCreateVisitIndex;

class Visitor extends BaseVisitor
{
    /**
     * Create a visit log.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @return void
     */
    public function visit(Model $model = null)
    {
        foreach ($this->except as $path) {
            if ($this->request->is($path)) {
                return;
            }
        }

        UpdateCreateVisitIndex::dispatch($model, $this->prepareLog());
    }

    /**
     * Retrieve request's url
     *
     * @return string
     */
    public function url() : string
    {
        return $this->request->url();
    }

    /**
     * Returns logs
     *
     * @return array
     */
    public function getLog()
    {
        return $this->prepareLog();
    }
}