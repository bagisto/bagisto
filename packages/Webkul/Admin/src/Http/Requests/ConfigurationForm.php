<?php

namespace Webkul\Admin\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
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
                if (! $this->has("{$key}.delete")) {
                    return [$key => $this->getValidationRules($field['validation'] ?? 'nullable')];
                }

                return [];
            })->toArray();
        })->toArray();
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
                'phone'    => new PhoneNumber,
                'postcode' => new PostCode,
                'decimal'  => new Decimal,
                default    => $rule,
            };
        }, $validations);
    }
}
