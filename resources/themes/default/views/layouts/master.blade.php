<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>{{ config('app.name') }}</title>

</head>

<body class="bg-white text-gray-800">

    {{-- HEADER --}}
    @include('shop::components.header')

    @yield('main-content')  

     {{-- FOOTER --}}
    @include('shop::components.footer')
    
</body>

</html>