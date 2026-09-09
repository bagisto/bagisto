<?php

namespace Webkul\Admin\Services;

use Illuminate\Support\Facades\Artisan;

class CacheManagerService
{
    /**
     * Available clear actions, each running one command or a list of them in order.
     */
    protected array $clearActions = [
        'clear-all' => ['optimize:clear', 'responsecache:clear'],
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

        $commands = (array) $allActions[$action];

        $command = implode(' && php artisan ', $commands);

        try {
            $output = [];

            foreach ($commands as $each) {
                $exitCode = Artisan::call($each);

                $output[] = trim(Artisan::output());

                if ($exitCode !== 0) {
                    return [
                        'success' => false,
                        'message' => $this->message($action, 'failed'),
                        'output' => trim(implode("\n", array_filter($output))),
                        'command' => $command,
                    ];
                }
            }

            return [
                'success' => true,
                'message' => $this->message($action, 'success'),
                'output' => trim(implode("\n", array_filter($output))),
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
     * What an action answers with: its own sentence when it has one, and otherwise a message
     * naming the action the way the button that ran it does.
     */
    protected function message(string $action, string $outcome): string
    {
        $key = 'admin::app.configuration.index.cache-management.results.'.$action;

        if (
            $outcome === 'success'
            && ($result = trans($key)) !== $key
        ) {
            return $result;
        }

        return trans(
            'admin::app.configuration.index.cache-management.action-'.$outcome,
            ['action' => $this->label($action)]
        );
    }

    /**
     * The name the configuration screen gives an action, falling back to its own key.
     */
    protected function label(string $action): string
    {
        $key = 'admin::app.configuration.index.cache-management.actions.'.$action;

        return ($label = trans($key)) === $key ? $action : $label;
    }
}
