<?php

namespace Webkul\Core\Traits;

trait CoreConfigField
{
    /**
     * Get name field for forms in configuration page.
     *
     * @param  string  $key
     * @return string
     */
    public function getNameField($key)
    {
        $nameField = '';

        foreach (explode('.', $key) as $key => $field) {
            $nameField .= $key === 0 ? $field : '[' . $field . ']';
        }

        return $nameField;
    }

    /**
     * Get validations for forms in configuration page.
     *
     * @param  array  $field
     * @return string
     */
    public function getValidations($field)
    {
        return isset($field['validation'])
            ? $field['validation']
            : '';
    }

    /**
     * Get value from repositories, if developer wants to do.
     *
     * @param  array  $field
     * @return mixed
     */
    public function getValueByRepository($field)
    {
        if (isset($field['repository'])) {
            $temp = explode('@', $field['repository']);
            $class = app(current($temp));
            $method = end($temp);
            return $class->$method();
        }

        return null;
    }

    /**
     * Get dependent field or value based on arguments.
     *
     * @param  array  $field
     * @param  string  $fieldOrValue
     * @return string
     */
    public function getDependentFieldOrValue($field, $fieldOrValue = 'field')
    {
        $depends = explode(':', $field['depend']);

        return $fieldOrValue === 'field'
            ? current($depends) : end($depends);
    }

    /**
     * Get dependent field options.
     *
     * @param  array  $field
     * @param  array  $dependentValues
     * @return mixed
     */
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

    /**
     * Get channel/locale indicator for form fields. So, that form fields can be detected,
     * whether it is channel based or locale based or both.
     *
     * @param  array  $field
     * @param  string  $channel
     * @param  string  $locale
     * @return string
     */
    public function getChannelLocaleInfo($field, $channel, $locale)
    {
        $info = [];

        if (
            isset($field['channel_based'])
            && $field['channel_based']
        ) {
            $info[] = $channel;
        }

        if (
            isset($field['locale_based'])
            && $field['locale_based']
        ) {
            $info[] = $locale;
        }

        return ! empty($info) ? '[' . implode(' - ', $info) . ']' : '';
    }
}
