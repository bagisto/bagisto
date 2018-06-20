<!DOCTYPE html>
<html lang="{{ config('app.locale') }}" @if (config('multilingual.rtl')) dir="rtl" @endif>
    <head>
        <title>@yield('page_title')</title>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <link rel="stylesheet" href="{{ asset('vendor/webkul/admin/assets/css/admin.css') }}">

        @yield('css')
        
        @yield('head')
    </head>
    <body>
        @include ('admin::layouts.nav-left')

        <div class="container">

            @include ('admin::layouts.nav-top')

            @yield('content')

        </div>

        @yield('javascript')
    </body>
</html>