<?php

namespace Webkul\Ui\DataGrid\Traits;

trait ProvideExceptionHandler
{
    /**
     * Required column keys.
     *
     * @var array
     */
    protected array $requiredColumnKeys = ['index', 'label'];

    /**
     * Required action keys.
     *
     * @var array
     */
    protected array $requiredActionKeys = ['title', 'method', 'route', 'icon'];

	/**
	 * This will check the keys which are needed for column.
	 *
	 * @param array $column
	 * @throws \Webkul\Ui\Exceptions\ColumnKeyException add column failed
	 * @return void
	 */
	public function checkRequiredColumnKeys($column): void
	{
		$this->checkRequiredKeys($this->requiredColumnKeys, $column, function ($missingKeys) {
			$message = 'Missing Keys: ' . implode(', ', $missingKeys);

			throw new \Webkul\Ui\Exceptions\ColumnKeyException($message);
		});
	}

	/**
	 * This will check the keys which are needed for action.
	 *
	 * @param array $action
	 * @throws \Webkul\Ui\Exceptions\ActionKeyException add action failed
	 * @return void
	 */
	public function checkRequiredActionKeys(array $action): void
	{
		$this->checkRequiredKeys($this->requiredActionKeys, $action, function ($missingKeys) {
			$message = 'Missing Keys: ' . implode(', ', $missingKeys);

			throw new \Webkul\Ui\Exceptions\ActionKeyException($message);
		});
	}

	/**
	 * Check required keys.
	 *
	 * @param array    $requiredKeys
	 * @param array    $actualKeys
	 * @param \Closure $operation
	 * @return void|\Closure
	 */
	public function checkRequiredKeys(array $requiredKeys, array $actualKeys, \Closure $operation)
	{
		$requiredKeys = array_flip($requiredKeys);

		$missingKeys = array_flip(array_diff_key($requiredKeys, $actualKeys));

		return !empty($missingKeys) ? $operation($missingKeys) : null;
	}
}
