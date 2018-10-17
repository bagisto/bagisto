@extends('admin::layouts.content')

@section('page_title')

@stop

@section('content')
    <div class="error-container" style="width: 100%; display: flex; justify-content: center;">

        <div class="wrapper" style="display: flex; height: 60vh; width: 100%;
            justify-content: start; align-items: center;">

            <div class="error-box"  style="width: 50%">

                <div class="error-title" style="font-size: 100px;color: #5E5E5E"> {{ $code }}</div>

                <div class="error-messgae" style="font-size: 24px;color: #5E5E5E">Page Not Found</div>

                <div class="error-description" style="margin-top: 20px;margin-bottom: 20px;color: #242424">The Page you are looking for doesnt exist or have secrately escaped;head backm to home and make a fresh move again.</div>

                <a href="{{ route('admin.dashboard.index') }}">GO TO HOME</a>

            </div>

            <div class="error-graphic icon-404" style="margin-left: 10% ;"></div>

        </div>

    </div>
@stop