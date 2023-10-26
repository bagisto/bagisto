<?php

namespace Webkul\Admin\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CartRuleRequest extends FormRequest
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
        $rules = [
            'name'                => 'required',
            'channels'            => 'required|array|min:1',
            'customer_groups'     => 'required|array|min:1',
            'coupon_type'         => 'required',
            'use_auto_generation' => 'required_if:coupon_type,==,1',
            'starts_from'         => 'nullable|date',
            'ends_till'           => 'nullable|date|after_or_equal:starts_from',
            'action_type'         => 'required',
            'discount_amount'     => 'required|numeric',
        ];

        if (! request('id')) {
            $rules = array_merge($rules, [
                'coupon_code'  => 'required_if:use_auto_generation,==,0|unique:cart_rule_coupons,code',
            ]);
        }

        if (
            request()->has('action_type')
            && request()->action_type == 'by_percent'
        ) {
            $rules = array_merge($rules, [
                'discount_amount' => 'required|numeric|min:0|max:100',
            ]);
        }

        return $rules;
    }
}
