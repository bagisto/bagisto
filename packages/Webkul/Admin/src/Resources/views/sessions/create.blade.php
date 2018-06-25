<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
    <head>
        <title>@yield('page_title')</title>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <link rel="stylesheet" href="{{ asset('vendor/webkul/admin/assets/css/admin.css') }}">
    </head>
    <body>
        <div class="container">

            <form method="POST" action="login">
                @csrf

                <div class="element-block">
                    <label for="email" class="field-label"></label>
                    <div class="field-block">
                        <input type="text" class="field" id="email" name="email"/> 
                    </div>
                </div>

                <div class="element-block">
                    <label for="password" class="field-label"></label>
                    <div class="field-block">
                        <input type="password" class="field" id="password" name="password"/> 
                    </div>
                </div>

                @if (count($errors))
                    @foreach ($errors->all() as $error)

                        {{ $error }}

                    @endforeach
                @endif

                <div class="button-block">
                    <button type="submit" class="button">Login</button>
                </div>
            </form>

        </div>
    </body>
</html>