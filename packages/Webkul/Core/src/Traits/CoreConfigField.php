<?php

namespace Webkul\Core\Traits;

trait CoreConfigField
{
    public function getNameField($key)
    {
        $nameField = '';

        foreach (explode('.', $key) as $key => $field) {
            $nameField .= $key === 0 ? $field : '[' . $field . ']';
        }

        return $nameField;
    }

    public function getValidations($field)
    {
        return isset($field['validation'])
            ? $field['validation']
            : '';
    }

    public function getValueByRepository($field)
    {
        if (isset($field['repository'])) {
            $temp = explode("@", $field['repository']);
            $class = app(current($temp));
            $method = end($temp);
            return $class->$method();
        }

        return null;
    }

    public function getDependentFieldOrValue($field, $fieldOrValue = 'field')
    {
        $depends = explode(":", $field['depend']);

        return $fieldOrValue === 'field'
            ? current($depends) : end($depends);
    }

    public function getDependentFieldOptions($field, $dependentValues)
    {
        $fieldOptions = null;

        if ($dependentValues) {
            $i = 0;

            foreach ($dependentValues as $key => $result) {
                $data['title'] = $result;
                $data['value'] = $key;
                $options[$i] = $data;
                $i++;
            }

            $fieldOptions = $options;
        }

        return ! isset($field['options'])
            ? ''
            : $fieldOptions;
    }

    public function getChannelLocaleInfo($field, $channel, $locale)
    {
        $info = [];

        if (isset($field['channel_based']) && $field['channel_based']) {
            $info[] = $channel;
        }

        if (isset($field['locale_based']) && $field['locale_based']) {
            $info[] = $locale;
        }

        return ! empty($info) ? '[' . implode(' - ', $info) . ']' : '';
    }
}