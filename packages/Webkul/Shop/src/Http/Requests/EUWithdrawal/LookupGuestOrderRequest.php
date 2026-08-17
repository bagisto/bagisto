<?php

namespace Webkul\Shop\Http\Requests\EUWithdrawal;

use Illuminate\Foundation\Http\FormRequest;

class LookupGuestOrderRequest extends FormRequest
{
    /**
     * Determine if the request is authorized.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Validation rules for the request.
     *
     * @return array<string, array<int, string>>
     */
    public function rules(): array
    {
        return [
            'order_increment_id' => ['required', 'max:50'],
            'email' => ['required', 'email', 'max:191'],
        ];
    }
}
