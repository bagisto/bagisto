<?php

namespace Webkul\Admin\CommandPalette;

class RecordSources
{
    /**
     * Where the declared sources are read from.
     */
    const CONFIG_KEY = 'command_palette.records';

    /**
     * The record sources the signed in admin may search.
     *
     * These are searched live rather than indexed, because the records themselves change
     * far too often to hold in the palette's index.
     */
    public function all(): array
    {
        $sources = [];

        foreach (config(self::CONFIG_KEY, []) as $source) {
            if (
                ! empty($source['permission'])
                && ! bouncer()->hasPermission($source['permission'])
            ) {
                continue;
            }

            if (! $endpoint = $this->routeFor($source['endpoint'] ?? null)) {
                continue;
            }

            $sources[] = [
                'key' => $source['key'],
                'title' => trans($source['title']),
                'endpoint' => $endpoint,
                'link' => $this->routeFor($source['link'] ?? null, [':id']),
                'label' => $source['label'] ?? ['name'],
                'prefix' => $source['prefix'] ?? '',
                'meta' => $source['meta'] ?? null,
                'icon' => $source['icon'] ?? null,
            ];
        }

        return $sources;
    }

    /**
     * A route's address, or nothing when it is not registered.
     */
    protected function routeFor(?string $name, array $params = []): ?string
    {
        if (! $name) {
            return null;
        }

        try {
            return route($name, $params);
        } catch (\Throwable) {
            return null;
        }
    }
}
