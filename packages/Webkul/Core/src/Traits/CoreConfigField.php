<?php

namespace Webkul\Core\Traits;

trait CoreConfigField
{
    /**
     * Laravel to Vee Validation mappings.
     *
     * @var array
     */
    protected $veeValidateMappings = [
        'min'=> 'min_value',
    ];

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
            $nameField .= $key === 0 ? $field : '['.$field.']';
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
        $field['validation'] = $field['validation'] ?? '';

        foreach ($this->veeValidateMappings as $laravelRule => $veeValidateRule) {
            $field['validation'] = str_replace($laravelRule, $veeValidateRule, $field['validation']);
        }

        return $field['validation'];
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
        $depends = explode(':', $field['depends']);

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
        if (
            empty($field['options'])
            || ! $dependentValues
        ) {
            return '';
        }

        $options = [];

        if (is_callable($dependentValues)) {
            $dependentValues = $dependentValues();
        }

        foreach ($dependentValues as $key => $result) {
            $options[] = [
                'title' => $result,
                'value' => $key,
            ];
        }

        return $options;
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

        if (! empty($field['channel_based'])) {
            $info[] = $channel;
        }

        if (! empty($field['locale_based'])) {
            $info[] = $locale;
        }

        return ! empty($info) ? '['.implode(' - ', $info).']' : '';
    }
}
