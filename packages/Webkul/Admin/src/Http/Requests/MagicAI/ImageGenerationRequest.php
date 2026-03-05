<?php

namespace Webkul\Admin\Http\Requests\MagicAI;

use Illuminate\Foundation\Http\FormRequest;

class ImageGenerationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, array<int, string>>
     */
    public function rules(): array
    {
        return [
            'prompt' => ['required', 'string'],
            'model' => ['nullable', 'string'],
            'n' => ['nullable', 'integer', 'min:1', 'max:10'],
            'size' => ['required', 'in:1:1,2:3,3:2'],
            'quality' => ['nullable', 'in:high,medium,low'],
        ];
    }
}
