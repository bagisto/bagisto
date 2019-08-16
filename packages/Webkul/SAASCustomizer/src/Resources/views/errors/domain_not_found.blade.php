@extends('saas::companies.layouts.master')

@section('page_title')
    {{ $status }} {{ $message }}
@stop

@section('content-wrapper')

    <div class="error-container" style="padding: 40px; width: 100%; display: flex; flex-direction: row; justify-content: center;">

        <div class="wrapper" style="display: flex; height: 60vh; width: 100%;
            justify-content: start; align-items: center;">

            <div class="error-box"  style="width: 50%">
                <div class="error-title" style="font-size: 24px; color: #5E5E5E;">
                    <img class="mb-10" src="{{ asset('vendor/webkul/saas/assets/images/compass.svg') }}" style="margin-right: 15px;" />

                    <br/>

                    {{ $message }}
                </div>

                <div class="error-messgae" style="font-size: 16px; color: #242424; margin-top: 40px;">
                    {!! __('saas::app.custom-errors.domain-message', [
                        'domain' => $_SERVER['SERVER_NAME']
                    ]) !!}.
                </div>

                <div class="error-description" style="margin-top: 20px; margin-bottom: 20px; color: #242424">
                    {!! __('saas::app.custom-errors.domain-desc', [
                        'domain' => $_SERVER['SERVER_NAME']
                    ]) !!}

                    <br/>

                    <a href="{{ env('APP_URL') }}" class="mt-10 btn btn-lg btn-primary">Contact Us</a>
                </div>
            </div>

            <div class="error-graphic icon-404" style="margin-left: 10%;"></div>
        </div>

    </div>

@stop