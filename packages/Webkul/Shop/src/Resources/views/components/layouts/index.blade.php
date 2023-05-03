<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

<head>
    <title>{{ $title ?? '' }}</title>

    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="base-url" content="{{ url()->to('/') }}">
    <meta http-equiv="content-language" content="{{ app()->getLocale() }}">

    {{
        Vite::useHotFile('shop-vite.hot')
            ->useBuildDirectory('themes/default/build')
            ->withEntryPoints(['src/Resources/assets/css/app.css', 'src/Resources/assets/js/app.js'])
    }}

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap"
        rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display&display=swap" rel="stylesheet">

    @if ($favicon = core()->getCurrentChannel()->favicon_url)
        <link rel="icon" sizes="16x16" href="{{ $favicon }}" />
    @else
        <link rel="icon" sizes="16x16" href="{{ bagisto_asset('images/favicon.ico') }}" />
    @endif

    {{-- Head Slot to insert custom SEO tags, CSS etc --}}
    {{ $head ?? '' }}

    <style>
        {!! core()->getConfigData('general.content.custom_scripts.custom_css') !!}
    </style>

    {!! view_render_event('bagisto.shop.layout.head') !!}
</head>

<body>
    {!! view_render_event('bagisto.shop.layout.body.before') !!}

    <div id="app">
        {{-- Flash Message Blade Component --}}
        <x-flash-group></x-flash-group>

        {{-- Page Header Blade Component --}}
        <x-layouts.header />

        {!! view_render_event('bagisto.shop.layout.content.before') !!}

        {{-- Page Content Blade Component --}}
        {{ $slot }}

        {!! view_render_event('bagisto.shop.layout.content.after') !!}

        {{-- Page Features Blade Component --}}
        <x-layouts.features />

        {{-- Page Footer Blade Component --}}
        <x-layouts.footer />
    </div>

    {!! view_render_event('bagisto.shop.layout.body.after') !!}

    <script type="text/javascript">
        window.serverErrors = [];

        @if (count($errors))
            window.serverErrors = @json($errors->getMessages());
        @endif
    </script>

    {{-- Footer Slot, here you can add custom scripts --}}
    {{ $head ?? '' }}
</body>

</html>
