<?php
namespace Webkul\Ui\DataGrid\Helpers;

use Illuminate\Http\Request;

class MassAction
{
    public function validateSchemaMassAction($attributes)
    {
        $operations_validated = false;
        $columns_validated = true;
        foreach ($attributes['operations'] as $operation) {
            if (array_key_exists('route', $operation) && array_key_exists('method', $operation) && array_key_exists('label', $operation) && array_key_exists('columns', $operation)) {
                $operations_validated = true;
            }
            if ($operations_validated) {
                foreach ($operation['columns'] as $column) {
                    if (array_key_exists('name', $operation) && array_key_exists('label', $operation) && array_key_exists('type', $operation)) {
                        $columns_validated = true;
                    }
                }
            }
        }
        if ($columns_validated && $operations_validated) {
            return true;
        } else {
            throw new \Exception('Schema is invalid for mass actions');
        }
    }
}
