<?php

namespace Webkul\Admin\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Webkul\User\Repositories\RoleRepository;

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
            'name' => 'required|max:255',
            'email' => ['required', 'max:255', 'email', Rule::unique('admins', 'email')->ignore($this->id)],
            'password' => 'nullable|min:6|confirmed',
            'password_confirmation' => 'nullable|required_with:password|same:password',
            'status' => 'sometimes',
            'role_id' => [
                'bail',
                'required',
                'integer',
                'exists:roles,id',
                function ($attribute, $value, $fail) {
                    if (! bouncer()->canGrantRole(app(RoleRepository::class)->find($value))) {
                        $fail(trans('admin::app.settings.users.role-not-grantable'));
                    }
                },
            ],
            'image' => 'array',
            'image.*' => 'mimes:jpeg,jpg,png,gif|max:10000',
        ];
    }

    /**
     * Get the custom attribute names for validator errors.
     *
     * @return array
     */
    public function attributes()
    {
        return [
            'image.*' => trans('admin::app.components.media.images.image'),
        ];
    }
}
