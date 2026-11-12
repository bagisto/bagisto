<?php

namespace Webkul\Admin\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Webkul\Admin\Notifications\AdminNotifications;

class NotificationFeedRequest extends FormRequest
{
    /**
     * Determine if the request is authorized or not.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'group' => ['nullable', 'string', Rule::in(array_keys(app(AdminNotifications::class)->groups()))],
            'unread' => ['nullable', 'boolean'],
            'per_page' => ['nullable', 'integer', 'min:1', 'max:50'],
        ];
    }

    /**
     * The types asked for: the chosen group's, or every type the admin may see.
     *
     * @return list<string>
     */
    public function types(): array
    {
        $adminNotifications = app(AdminNotifications::class);

        return $this->filled('group')
            ? $adminNotifications->typesIn($this->input('group'))
            : $adminNotifications->types();
    }
}
