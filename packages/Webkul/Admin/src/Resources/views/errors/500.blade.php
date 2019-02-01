@extends(auth()->guard('admin')->check() ? 'admin::layouts.master' : 'shop::layouts.master')

@section('page_title')
    {{ __('admin::app.error.500.page-title') }}
@stop

@section('content-wrapper')

    <div class="error-container" style="padding: 40px; width: 100%; display: flex; justify-content: center;">

        <div class="wrapper" style="display: flex; height: 60vh; width: 100%;
            justify-content: start; align-items: center;">

            <div class="error-box"  style="width: 50%">

                <div class="error-title" style="font-size: 100px;color: #5E5E5E">
                    {{ __('admin::app.error.500.name') }}
                </div>

                <div class="error-messgae" style="font-size: 24px;color: #5E5E5E">
                    {{ __('admin::app.error.500.title') }}
                </div>

                <div class="error-description" style="margin-top: 20px;margin-bottom: 20px;color: #242424">
                    {{ __('admin::app.error.500.message') }}
                </div>

                <a href="{{ route('admin.dashboard.index') }}">
                    {{ __('admin::app.error.go-to-home') }}
                </a>

            </div>

            <div class="error-graphic icon-404" style="margin-left: 10% ;"></div>

        </div>

    </div>

@stop