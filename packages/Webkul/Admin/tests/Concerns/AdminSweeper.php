<?php

namespace Webkul\Admin\Tests\Concerns;

trait AdminSweeper
{
    /**
     * Clean everything.
     */
    public function cleanAll(): void
    {
        $this->cleanDatabase();

        $this->cleanElasticSearchIndices();
    }

    /**
     * Clean database.
     */
    public function cleanDatabase(): void
    {
    }

    /**
     * Clean elastic search indices.
     */
    public function cleanElasticSearchIndices(): void
    {
    }
}
