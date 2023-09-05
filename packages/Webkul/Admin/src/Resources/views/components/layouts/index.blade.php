<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ core()->getCurrentLocale()->direction }}">

<head>
    <title>{{ $title ?? '' }}</title>

    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="base-url" content="{{ url()->to('/') }}">
    <meta name="currency-code" content="{{ core()->getCurrentCurrencyCode() }}">
    <meta http-equiv="content-language" content="{{ app()->getLocale() }}">

    @stack('meta')

    {{-- <link
        rel="icon"
        sizes="16x16"
        href="{{ core()->getCurrentChannel()->favicon_url ?? bagisto_asset('images/favicon.ico') }}"
    /> --}}

    @bagistoVite(['src/Resources/assets/css/app.css', 'src/Resources/assets/js/app.js'])

    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap"
        rel="stylesheet"
    />

    <link
        href="https://fonts.googleapis.com/css2?family=DM+Serif+Display&display=swap"
        rel="stylesheet"
    />

    @if ($favicon = core()->getConfigData('general.design.admin_logo.favicon', core()->getCurrentChannelCode()))
        <link 
            type="image/x-icon"
            href="{{ Storage::url($favicon) }}" 
            rel="shortcut icon"
            sizes="16x16"
        >
    @else
        <link 
            type="image/x-icon"
            href="{{ asset('vendor/webkul/ui/assets/images/favicon.ico') }}" 
            rel="shortcut icon"
            sizes="16x16"
        />
    @endif
    
    @stack('styles')

    <style>
        {!! core()->getConfigData('general.content.custom_scripts.custom_css') !!}
    </style>

    {!! view_render_event('bagisto.shop.layout.head') !!}
</head>

<body>
    {!! view_render_event('bagisto.shop.layout.body.before') !!}

    <div id="app">
        {{-- Flash Message Blade Component --}}
        <x-admin::flash-group />

        {!! view_render_event('bagisto.shop.layout.content.before') !!}

        {{-- Page Header Blade Component --}}
        <x-admin::layouts.header />

        <div
            class="flex gap-[16px] group/container {{ ($_COOKIE['sidebar_collapsed'] ?? 0) ? 'sidebar-collapsed' : '' }}"
            ref="appLayout"
        >
            {{-- Page Sidebar Blade Component --}}
            <x-admin::layouts.sidebar />

            <div class="flex-1 h-full max-w-full px-[16px] pt-[11px] pb-[22px] ltr:pl-[286px] rtl:pr-[286px] max-lg:!px-[16px] transition-all duration-300 group-[.sidebar-collapsed]/container:ltr:pl-[85px] group-[.sidebar-collapsed]/container:rtl:pr-[85px]">
                {{-- Added dynamic tabs for third level menus  --}}
                {{-- Todo @suraj-webkul need to optimize below statement. --}}
                @if (! request()->routeIs('admin.configuration.index'))
                    <x-admin::layouts.tabs />
                @endif

                {{-- Page Content Blade Component --}}
                {{ $slot }}
            </div>
        </div>

        {!! view_render_event('bagisto.shop.layout.content.after') !!}
    </div>

    {!! view_render_event('bagisto.shop.layout.body.after') !!}

    <script type="text/javascript">
        window.serverErrors = [];

        @if (count($errors))
            window.serverErrors = @json($errors->getMessages());
        @endif
    </script>

    @stack('scripts')

    <script type="text/javascript">
        {!! core()->getConfigData('general.content.custom_scripts.custom_javascript') !!}
    </script>
</body>

</html>
