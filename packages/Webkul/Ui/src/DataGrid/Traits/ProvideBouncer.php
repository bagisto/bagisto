<?php

namespace Webkul\Ui\DataGrid\Traits;

trait ProvideBouncer
{
    use ProvideRouteResolver;

    /**
     * Check permissions.
     *
     * @param  array     $action
     * @param  bool      $specialPermission
     * @param  \Closure  $operation
     * @return void
     */
    private function checkPermissions($action, $specialPermission, $operation, $nameKey = 'title')
    {
        $currentRouteACL = $this->fetchCurrentRouteACL($action);

        $eventName = isset($action[$nameKey]) ? $this->generateEventName($action[$nameKey]) : null;

        if (bouncer()->hasPermission($currentRouteACL['key'] ?? null) || $specialPermission) {
            $operation($action, $eventName);
        }
    }

    /**
     * Generate event name.
     *
     * @param  string  $titleOrLabel
     * @return string
     */
    private function generateEventName($titleOrLabel)
    {
        $eventName = explode(' ', strtolower($titleOrLabel));
        return implode('.', $eventName);
    }
}