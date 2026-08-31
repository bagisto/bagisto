<?php

namespace Webkul\Product\Contracts;

interface SearchEngineConnection
{
    /**
     * Apply the recorded settings, or the values passed in, to the engine's configuration.
     */
    public function configure(array $overrides = []): void;

    /**
     * Ask the engine whether it is reachable, through the values passed in when given.
     */
    public function probe(array $overrides = []): array;

    /**
     * Whether the given values describe the configuration as it is already recorded.
     */
    public function describesRecorded(array $overrides): bool;
}
