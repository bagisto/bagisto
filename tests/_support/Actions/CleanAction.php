<?php

namespace Actions;

trait CleanAction
{
    /**
     * Clean all fields.
     *
     * @param  array  $fields
     * @return array
     */
    public function cleanAllFields(array $fields)
    {
        return collect($fields)->map(function ($field, $key) {
            return $this->cleanField($field);
        })->toArray();
    }

    /**
     * Clean field.
     *
     * @param  string  $field
     * @return string
     */
    public function cleanField($field)
    {
        return preg_replace('/[^A-Za-z0-9 ]/', '', $field);
    }
}
