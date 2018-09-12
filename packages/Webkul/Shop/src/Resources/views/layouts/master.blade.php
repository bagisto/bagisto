<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">

<head>

    <title>@yield('page_title')</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{ bagisto_asset('css/shop.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/webkul/ui/assets/css/ui.css') }}">

    @yield('head')

    @yield('css')

</head>

<body>

    <div id="app">

        <div class="main-container-wrapper">

            @include('shop::layouts.header.index')

            @yield('slider')

            <div class="content-container">

                @yield('content-wrapper')

            </div>

        </div>

        @include('shop::layouts.footer')

    </div>

    <script type="text/javascript">
        window.flashMessages = [];

        @if($success = session('success'))
            window.flashMessages = [{'type': 'alert-success', 'message': "{{ $success }}" }];
        @elseif($warning = session('warning'))
            window.flashMessages = [{'type': 'alert-warning', 'message': "{{ $warning }}" }];
        @elseif($error = session('error'))
            window.flashMessages = [{'type': 'alert-error', 'message': "{{ $error }}" }];
        @endif

        window.serverErrors = [];
        @if (count($errors))
            window.serverErrors = @json($errors->getMessages());
        @endif
    </script>

    <script type="text/javascript" src="{{ bagisto_asset('js/shop.js') }}"></script>
    <script type="text/javascript" src="{{ asset('vendor/webkul/ui/assets/js/ui.js') }}"></script>

    @stack('scripts')

</body>

</html>