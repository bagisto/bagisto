<?php

namespace Webkul\Admin\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Webkul\Core\Rules\CommaSeparatedInteger;
use Webkul\Core\Rules\Decimal;
use Webkul\Core\Rules\PhoneNumber;
use Webkul\Core\Rules\PostCode;

class ConfigurationForm extends FormRequest
{
    /**
     * Determine if the Configuration is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules of every configuration section the request submits a field of, read from
     * the configuration itself rather than from anything the request describes.
     *
     * @return array
     */
    public function rules()
    {
        return collect(config('core'))
            ->filter(fn ($item) => $this->submitsFieldOf($item))
            ->mapWithKeys(function ($item) {
                return collect($item['fields'])->mapWithKeys(function ($field) use ($item) {
                    $key = "{$item['key']}.{$field['name']}";

                    if ($this->has("{$key}.delete")) {
                        return [];
                    }

                    if (! $this->dependencyIsMet($item['key'], $field)) {
                        return [];
                    }

                    return [$key => $this->getValidationRules($field['validation'] ?? 'nullable')];
                })->toArray();
            })
            ->toArray();
    }

    /**
     * Whether the request submits a value for any field of a configuration section.
     */
    protected function submitsFieldOf(array $item): bool
    {
        return collect($item['fields'] ?? [])
            ->contains(fn ($field) => $this->has("{$item['key']}.{$field['name']}"));
    }

    /**
     * Determine whether a field's depend condition is met by the submitted values, since a field the
     * depend hides is never submitted and validating it would reject a form the admin cannot fill.
     */
    protected function dependencyIsMet(string $itemKey, array $field): bool
    {
        if (empty($field['depends'])) {
            return true;
        }

        [$name, $values] = array_pad(explode(':', $field['depends'], 2), 2, '');

        return in_array(
            (string) $this->input("{$itemKey}.{$name}"),
            explode(',', $values),
            true
        );
    }

    /**
     * Transform validation rules into an array and map custom validation rules.
     *
     * @param  string|array  $validation
     * @return array
     */
    protected function getValidationRules($validation)
    {
        $validations = is_array($validation) ? $validation : explode('|', $validation);

        return array_map(function ($rule) {
            return match ($rule) {
                'comma_separated_integer' => new CommaSeparatedInteger,
                'decimal' => new Decimal,
                'phone' => new PhoneNumber,
                'postcode' => new PostCode,
                default => $rule,
            };
        }, $validations);
    }
}
