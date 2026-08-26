<?php

namespace Webkul\Product\Contracts;

interface SearchEngineConnection
{
    /**
     * Apply the recorded settings to the engine's own configuration.
     *
     * A setting left empty is passed over, so the environment stays in charge of
     * anything the admin has not filled in.
     */
    public function configure(): void;

    /**
     * Ask the engine whether it is reachable and usable.
     *
     * Returns a status drawn from SearchEngineStatusEnum, plus whatever detail the
     * engine can report about itself.
     */
    public function probe(): array;
}
