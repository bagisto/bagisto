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
     * @return void
     */
    public function visit(?Model $model = null)
    {
        foreach ($this->except as $path) {
            if ($this->request->is($path)) {
                dd(1);

                return;
            }
        }
        UpdateCreateVisitIndex::dispatch($model, $this->prepareLog());
    }

    /**
     * Retrieve request's url
     */
    public function url(): string
    {
        return $this->request->url();
    }

    /**
     * Prepare log's data.
     *
     *
     * @throws \Exception
     */
    protected function prepareLog(): array
    {
        return array_merge(parent::prepareLog(), [
            'channel_id' => core()->getCurrentChannel()->id,
        ]);
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
