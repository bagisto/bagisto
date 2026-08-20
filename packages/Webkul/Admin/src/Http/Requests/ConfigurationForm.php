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
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return collect(request()->input('keys', []))->mapWithKeys(function ($item) {
            $data = json_decode($item, true);

            return collect($data['fields'])->mapWithKeys(function ($field) use ($data) {
                $key = "{$data['key']}.{$field['name']}";

                // Check delete key exist in the request
                if ($this->has("{$key}.delete")) {
                    return [];
                }

                if (! $this->dependencyIsMet($data['key'], $field)) {
                    return [];
                }

                return [$key => $this->getValidationRules($field['validation'] ?? 'nullable')];
            })->toArray();
        })->toArray();
    }

    /**
     * Determine whether a field's depend condition is met by the submitted values.
     *
     * A field the depend hides is never submitted, so validating it would reject a form the
     * admin cannot fill.
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
     * Transform validation rules into an array and map custom validation rules
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
