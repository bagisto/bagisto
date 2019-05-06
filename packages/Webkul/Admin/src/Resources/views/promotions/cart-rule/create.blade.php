@extends('admin::layouts.content')

@section('page_title')
    {{ __('admin::app.promotion.add-cart-rule') }}
@stop

@section('content')
    <div class="content">
        <form method="POST" action="{{ route('admin.customer.store') }}" @submit.prevent="onSubmit">

            <div class="page-header">
                <div class="page-title">
                    <h1>
                        <i class="icon angle-left-icon back-link" onclick="history.length > 1 ? history.go(-1) : window.location = '{{ url('/admin/dashboard') }}';"></i>

                        {{ __('admin::app.promotion.create-cart-rule') }}

                        {{ Config::get('carrier.social.facebook.url') }}
                    </h1>
                </div>

                <div class="page-action">
                    <button type="submit" class="btn btn-lg btn-primary">
                        {{ __('admin::app.customers.customers.save-btn-title') }}
                    </button>
                </div>
            </div>

            <div class="page-content">

                <div class="form-container">
                    @csrf()

                    <div v-for="(cart_attr, index) in cart_attrs">
                        <div class="control-group" style="display: flex; flex-direction: row;">
                            <label for="cart_attr" class="required">{{ __('admin::app.promotion.select-cart-attr') }}</label>

                            <select type="text" class="control" name="cart_attribute" v-model="cart_attr.cart_attribute" v-validate="'required'" value="{{ old('cart_attribute') }}" data-vv-as="&quot;{{ __('admin::app.promotion.general-info.cust-groups') }}&quot;">
                                <option disabled="disabled">Select Cart Attribute</option>
                                <option v-for="(cartAttribute, index) in cart_attrs" value="cartAttribute[index]">@{{ cartAttribute[index] }}</option>
                            </select>

                            <input type="text" class="control" v-model="cart_attr.condition_one" placeholder="select condition">
                            <input type="text" class="control" v-model="cart_attr.value_one" placeholder="enter value for condtion">
                            <input type="text" class="control" v-model="cart_attr.condition_two" placeholder="enter condition two">
                            <input type="text" class="control" v-model="cart_attr.value_two" placeholder="enter condition value">
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
@stop