<?php

namespace Webkul\Sales\Contracts;

interface Sequencer
{
    /**
     * Create and return the next sequence number for e.g. an order.
     *
     * @return string
     */
    public function generate(): string;
}
