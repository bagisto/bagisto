<?php

namespace Webkul\Admin\Services;

use Illuminate\Support\Facades\Artisan;

class CacheManagerService
{
    /**
     * Available clear actions.
     */
    protected array $clearActions = [
        'clear-all' => 'optimize:clear',
        'clear-config' => 'config:clear',
        'clear-cache' => 'cache:clear',
        'clear-compiled' => 'clear-compiled',
        'clear-events' => 'event:clear',
        'clear-routes' => 'route:clear',
        'clear-views' => 'view:clear',
    ];

    /**
     * Available build actions.
     */
    protected array $buildActions = [
        'build-all' => 'optimize',
        'build-config' => 'config:cache',
        'build-routes' => 'route:cache',
        'build-views' => 'view:cache',
    ];

    /**
     * Execute a cache action by key.
     */
    public function execute(string $action): array
    {
        $allActions = array_merge($this->clearActions, $this->buildActions);

        if (! isset($allActions[$action])) {
            return [
                'success' => false,
                'message' => trans('admin::app.configuration.index.cache-management.invalid-action'),
                'output' => '',
                'command' => '',
            ];
        }

        $command = $allActions[$action];

        try {
            $exitCode = Artisan::call($command);
            $rawOutput = Artisan::output();

            if ($exitCode !== 0) {
                return [
                    'success' => false,
                    'message' => trans('admin::app.configuration.index.cache-management.action-failed', ['action' => $command]),
                    'output' => trim($rawOutput),
                    'command' => $command,
                ];
            }

            return [
                'success' => true,
                'message' => trans('admin::app.configuration.index.cache-management.action-success', ['action' => $command]),
                'output' => trim($rawOutput),
                'command' => $command,
            ];
        } catch (\Throwable $e) {
            return [
                'success' => false,
                'message' => trans('admin::app.configuration.index.cache-management.action-exception', ['message' => $e->getMessage()]),
                'output' => $e->getMessage(),
                'command' => $command,
            ];
        }
    }

    /**
     * Get clear actions definitions for the view.
     */
    public function getClearActions(): array
    {
        return $this->clearActions;
    }

    /**
     * Get build actions definitions for the view.
     */
    public function getBuildActions(): array
    {
        return $this->buildActions;
    }
}
