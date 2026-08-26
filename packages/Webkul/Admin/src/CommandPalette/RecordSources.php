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
     * Searched live rather than indexed, because records change too often to hold.
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
                'node' => $source['node'] ?? null,
                'title' => trans($source['title']),
                'collection_title' => trans('admin::app.command-palette.all', [
                    'resource' => trans($source['title']),
                ]),
                'endpoint' => $endpoint,
                'index' => $this->routeFor($source['index'] ?? null),
                'link' => $this->routeFor($source['link'] ?? null, [':id']),
                'label' => $source['label'] ?? ['name'],
                'prefix' => $source['prefix'] ?? '',
                'meta' => $source['meta'] ?? null,
                'icon' => $source['icon'] ?? null,
                'actions' => $this->actionsFor($source),
            ];
        }

        return $sources;
    }

    /**
     * What a single record of a source opens, for the admins permitted to do it.
     */
    protected function actionsFor(array $source): array
    {
        $actions = [];

        foreach ($source['actions'] ?? [] as $action) {
            if (
                ! empty($action['permission'])
                && ! bouncer()->hasPermission($action['permission'])
            ) {
                continue;
            }

            if (! $link = $this->routeFor($action['route'] ?? null, [':id'])) {
                continue;
            }

            $actions[] = [
                'title' => trans($action['title']),
                'link' => $link,
                'icon' => $action['icon'] ?? null,
            ];
        }

        return $actions;
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
