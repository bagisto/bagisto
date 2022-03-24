@extends('admin::layouts.content')

@section('page_title')
    {{ __('admin::app.sales.transactions.create-title') }}
@stop

@section('content')
    <div class="content">
        <div class="page-header">
            <div class="page-title">
                <i class="icon angle-left-icon back-link" onclick="window.location = '{{ route('admin.sales.transactions.index') }}'"></i>

                <h1>{{ __('admin::app.sales.transactions.create-title') }}</h1>
            </div>
        </div>

        <div class="page-content">
            <form method="POST" action="{{ route('admin.sales.transactions.store') }}">
                <div class="form-container">
                    @csrf

                    <div class="control-group" :class="[errors.has('invoice_id') ? 'has-error' : '']">
                        <label for="invoice_id" class="required">
                            {{ __('admin::app.sales.transactions.invoice-id') }}
                        </label>

                        <input id="invoice_id" name="invoice_id" class="control" value="{{ old('invoice_id') }}" v-validate="'required'" data-vv-as="&quot;{{ __('admin::app.sales.transactions.invoice-id') }}&quot;" />

                        <span class="control-error" v-if="errors.has('invoice_id')">@{{ errors.first('invoice_id') }}</span>
                    </div>

                    <div class="control-group select" :class="[errors.has('payment_method') ? 'has-error' : '']">
                        <label for="payment-method" class="required">{{ __('admin::app.sales.transactions.payment-method') }} </label>

                        <select id="payment-method" name="payment_method" class="control" v-validate="'required'" data-vv-as="&quot;{{ __('admin::app.sales.transactions.payment-method') }}&quot;">
                            <option value="">{{ __('admin::app.select-option') }}</option>

                            @foreach ($payment_methods["paymentMethods"] as $paymentMethod)
                                @if($paymentMethod["method"] == "cashondelivery" || $paymentMethod["method"] == "moneytransfer")
                                    <option value="{{ $paymentMethod["method"] }}">{{ $paymentMethod["method_title"] }}</option>
                                @endif
                            @endforeach
                        </select>

                        <span class="control-error" v-if="errors.has('payment_method')">@{{ errors.first('payment_method') }}</span>
                    </div>

                    <div class="control-group" :class="[errors.has('amount') ? 'has-error' : '']">
                        <label for="transaction-amount" class="required">{{ __('admin::app.sales.transactions.transaction-amount') }}</label>

                        <input id="transaction-amount" name="amount" class="control" value="{{ old('amount') }}" v-validate="'required'" data-vv-as="&quot;{{ __('admin::app.sales.transactions.transaction-amount') }}&quot;">

                        <span class="control-error" v-if="errors.has('amount')">@{{ errors.first('amount') }}</span>
                    </div>

                    <button type="submit" class="btn btn-lg btn-primary">{{ __('admin::app.save') }}</button>
                </div>
            </form>
        </div>
    </div>
@stop