<?php

namespace Webkul\Admin\CommandPalette;

use Illuminate\Contracts\Foundation\Application;
use Webkul\Admin\CommandPalette\Contracts\Provider;

class CommandPalette
{
    /**
     * Registered providers, as class names resolved on demand.
     *
     * @var string[]
     */
    protected array $providers = [];

    /**
     * Create a new instance.
     */
    public function __construct(
        protected Application $app,
    ) {}

    /**
     * Register a provider of searchable items.
     */
    public function register(string $provider): void
    {
        if (in_array($provider, $this->providers)) {
            return;
        }

        $this->providers[] = $provider;
    }

    /**
     * The registered providers.
     *
     * @return string[]
     */
    public function providers(): array
    {
        return $this->providers;
    }

    /**
     * Everything the signed in admin may reach, ready for the palette.
     */
    public function items(): array
    {
        $items = [];

        foreach ($this->providers as $provider) {
            $resolved = $this->app->make($provider);

            if (! $resolved instanceof Provider) {
                continue;
            }

            foreach ($resolved->items() as $item) {
                $items[] = $item->toArray();
            }
        }

        return $items;
    }
}
