<?php

namespace Webkul\Ui\DataGrid\Traits;

trait ProvideBouncer
{
    use ProvideRouteResolver;

	/**
	 * Check permissions.
	 *
	 * @param array    $action
	 * @param bool     $specialPermission
	 * @param \Closure $operation
	 * @param string   $nameKey
	 * @return void
	 */
    private function checkPermissions(array $action, bool $specialPermission, \Closure $operation, string $nameKey = 'title'): void
	{
        $currentRouteACL = $this->fetchCurrentRouteACL($action);

        $eventName = isset($action[$nameKey]) ? $this->generateEventName($action[$nameKey]) : null;

		if ($specialPermission || bouncer()->hasPermission($currentRouteACL['key'] ?? null)) {
			$operation($action, $eventName);
		}
	}

    /**
     * Generate event name.
     *
     * @param string $titleOrLabel
     * @return string
     */
    private function generateEventName(string $titleOrLabel): string
	{
        $eventName = explode(' ', strtolower($titleOrLabel));
        return implode('.', $eventName);
    }
}
