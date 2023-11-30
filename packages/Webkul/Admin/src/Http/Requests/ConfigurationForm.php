<?php

namespace Webkul\Admin\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

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
        $keys = request()->input('keys');

        $this->rules = collect($keys)->mapWithKeys(function ($item) {
            $data = json_decode($item);

            $validationRules = collect($data->fields)->mapWithKeys(function ($field) use ($data) {
                $key = $data->key . '.' . $field->name;

                // Check delete key exist in the request
                if (! $this->has($key . '.delete')) {
                    $validation = isset($field->validation) ? $field->validation : '';

                    return [$key =>$validation];
                }

                return [];

                // Check if validation information is available in the field
                $validation = isset($field->validation) ? $field->validation : '';

                return [$key => $validation];
            })->toArray();

            return $validationRules;
        })->toArray();

        return $this->rules;
    }
}
