<?php

namespace Webkul\Admin\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserForm extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
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
        return [
            'name' => 'required',
            'email' => ['required', 'email', Rule::unique('admins', 'email')->ignore($this->id)],
            'password' => 'nullable|min:6|confirmed',
            'password_confirmation' => 'nullable|required_with:password|same:password',
            'status' => 'sometimes',
            'role_id' => 'required',
            'image' => 'array',
            'image.*' => 'mimes:jpeg,jpg,png,gif|max:10000',
        ];
    }
}
