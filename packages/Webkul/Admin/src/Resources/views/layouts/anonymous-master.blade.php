<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
    <head>
        <title>@yield('page_title')</title>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <link rel="icon" sizes="16x16" href="{{ asset('vendor/webkul/ui/assets/images/favicon.ico') }}" />

        <link rel="stylesheet" href="{{ asset('vendor/webkul/admin/assets/css/admin.css') }}">
        <link rel="stylesheet" href="{{ asset('vendor/webkul/ui/assets/css/ui.css') }}">

        <style>
            .container {
                text-align: center;
                position: absolute;
                width: 100%;
                height: 100%;
                display: table;
                z-index: 1;
                background: #F8F9FA;
            }
            .center-box {
                display: table-cell;
                vertical-align: middle;
            }
            .adjacent-center {
                width: 365px;
                display: inline-block;
                text-align: left;
            }

            .form-container .control-group .control {
                width: 100%;
            }

            h1 {
                font-size: 24px;
                font-weight: 600;
                margin-bottom: 30px;
            }

            .brand-logo {
                margin-bottom: 30px;
                text-align: center;
            }

            .footer {
                margin-top: 40px;
                padding: 0 20px;
            }

            .footer p {
                font-size: 14px;
                color: #8E8E8E;
                text-align: center;
            }

            .btn.btn-primary {
                width: 100%;
            }
        </style>

        @yield('css')
    </head>
    <body>
        <div id="app" class="container">

            <flash-wrapper ref='flashes'></flash-wrapper>

            <div class="center-box">
            
                <div class="adjacent-center">

                    <div class="brand-logo">
                        <img src="{{ asset('vendor/webkul/ui/assets/images/logo.png') }}" alt="Bagisto"/>
                    </div>

                    @yield('content')

                    <div class="footer">
                        <p>
                            © Copyright 2018 Webkul Software, All rights reserved.
                        </p>
                    </div>

                </div>

            </div>

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

        <script type="text/javascript" src="{{ asset('vendor/webkul/admin/assets/js/admin.js') }}"></script>
        <script type="text/javascript" src="{{ asset('vendor/webkul/ui/assets/js/ui.js') }}"></script>

        @yield('javascript')
        
    </body>
</html>