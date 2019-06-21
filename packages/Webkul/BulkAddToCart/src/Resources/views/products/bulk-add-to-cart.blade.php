@extends('shop::layouts.master')

@section('page_title')
    {{ __('bulkaddtocart::app.products.bulk-add-to-cart') }}
@endsection

@section('content-wrapper')

<div class="account-content">
    @include('shop::customers.account.partials.sidemenu')

    <div class="account-layout">

        <div class="account-head mb-15">
            <span class="account-heading">{{ __('bulkaddtocart::app.products.bulk-add-to-cart') }}</span>

            <div class="horizontal-rule"></div>
        </div>

        <div class="account-items-list">
            <form method="POST" action="{{ route('cart.bulk-add-to-cart.store') }}" enctype="multipart/form-data" @submit.prevent="onSubmit">
                @csrf()

                <div class="control-group" :class="[errors.has('file') ? 'has-error' : '']">
                    <label for="file" class="required">{{ __('bulkaddtocart::app.products.file') }}</label>
                    <input v-validate="'required'" type="file" class="control" id="file" name="file" data-vv-as="&quot;{{ __('bulkaddtocart::app.products.file') }}&quot;" value="{{ old('file') }}" style="padding-top: 5px"><br>
                    <span>{{ __('bulkaddtocart::app.products.allowed-type') }}</span>
                    <span><b>{{ __('bulkaddtocart::app.products.file-type') }}</b></span>
                    <span class="control-error" v-if="errors.has('file')">@{{ errors.first('file') }}</span>
                </div>

                <button type="submit" class="btn btn-lg btn-primary">
                    {{ __('bulkaddtocart::app.products.submit') }}
                </button>

            </form>

            <div class="download-sample" style="margin-top: 30px;">
                <span><b>{{ __('bulkaddtocart::app.products.download-sample') }}</b></span>
                <span>
                    <a href="{{ route('cart.bulk-add-to-cart.sample.download') }}">
                        <i class="icon sort-down-icon" style="position: absolute; margin-left: 30px; cursor: pointer;"></i>
                    </a>
                </span>
            </div>
        </div>

    </div>
</div>
@endsection