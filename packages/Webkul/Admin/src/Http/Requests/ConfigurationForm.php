<?php

namespace Webkul\Admin\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Webkul\Core\Rules\CommaSeparatedInteger;
use Webkul\Core\Rules\Decimal;
use Webkul\Core\Rules\PhoneNumber;
use Webkul\Core\Rules\PostCode;
use Webkul\Core\SystemConfig\DependsCondition;

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

                    $condition = new DependsCondition($field['depends'] ?? null);

                    $sibling = "{$item['key']}.{$condition->fieldName()}";

                    if (! $condition->permits($this->has($sibling), $this->input($sibling))) {
                        return [];
                    }

                    return [$key => array_merge(
                        $this->getValidationRules($field['validation'] ?? 'nullable'),
                        $this->getTypeRules($field['type'] ?? null),
                    )];
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
     * The rules a field's own type implies, so a number cannot be saved as a word or left blank.
     *
     * @return array<int, string>
     */
    protected function getTypeRules(?string $type): array
    {
        return match ($type) {
            'boolean' => ['in:0,1'],
            'number' => ['numeric'],
            default => [],
        };
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
