@extends('saas::companies.layouts.master')

@section('page_title')
    {{ $status }} {{ __('saas::app.exceptions.not-allowed-to-visit-this-section') }}
@stop

@section('content-wrapper')

    <div class="error-container" style="padding: 40px; width: 100%; display: flex; flex-direction: row; justify-content: center;">

        <div class="wrapper" style="display: flex; height: 60vh; width: 100%;
            justify-content: start; align-items: center;">

            <div class="error-box"  style="width: 50%">
                <div class="error-title" style="font-size: 24px; color: #5E5E5E; display: flex; flex-direction: row; align-items: center;">
                    <img src="{{ asset('vendor/webkul/saas/assets/images/block.svg') }}" style="margin-right: 15px;" /> Seller Blocked
                </div>

                <div class="error-messgae" style="font-size: 24px;color: #242424; margin-top: 40px;">
                    <b>{{ \Company::getCurrent()->name }}</b> {{ __('saas::app.custom-errors.blocked') }}.
                </div>

                <div class="error-description" style="margin-top: 20px; margin-bottom: 20px; color: #242424">
                    {{ __('saas::app.custom-errors.block-message') }}

                    {{-- <a class="btn btn-lg btn-primary">Contact Us</a> --}}
                </div>
            </div>

            <div class="error-graphic icon-404" style="margin-left: 10%;"></div>
        </div>

    </div>

@stop