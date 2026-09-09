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
     * Available page cache actions, which are offered from Full Page Cache rather than
     * alongside the application caches.
     */
    protected array $pageActions = [
        'clear-page-cache' => 'responsecache:clear',
    ];

    /**
     * What an action answers with, for the ones whose command name would tell the operator
     * running it nothing.
     */
    protected array $actionMessages = [
        'clear-page-cache' => [
            'success' => 'admin::app.configuration.index.cache-management.full-page-cache.settings.flush-success',
            'failed' => 'admin::app.configuration.index.cache-management.full-page-cache.settings.flush-failed',
        ],
    ];

    /**
     * Execute a cache action by key.
     */
    public function execute(string $action): array
    {
        $allActions = array_merge($this->clearActions, $this->buildActions, $this->pageActions);

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
                    'message' => $this->message($action, 'failed', $command),
                    'output' => trim($rawOutput),
                    'command' => $command,
                ];
            }

            return [
                'success' => true,
                'message' => $this->message($action, 'success', $command),
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

    /**
     * Get page cache actions definitions for the view.
     */
    public function getPageActions(): array
    {
        return $this->pageActions;
    }

    /**
     * What an action answers with, falling back to a message naming the command it ran.
     */
    protected function message(string $action, string $outcome, string $command): string
    {
        if (isset($this->actionMessages[$action][$outcome])) {
            return trans($this->actionMessages[$action][$outcome]);
        }

        return trans(
            'admin::app.configuration.index.cache-management.action-'.$outcome,
            ['action' => $command]
        );
    }
}
