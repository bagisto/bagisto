<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ core()->getCurrentLocale()->direction }}" class="{{ (request()->cookie('dark_mode') ?? 0) ? 'dark' : '' }}">
    <head>
        <title>{{ $title ?? '' }}</title>

        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="base-url" content="{{ url()->to('/') }}">
        <meta name="currency-code" content="{{ core()->getBaseCurrencyCode() }}">
        <meta http-equiv="content-language" content="{{ app()->getLocale() }}">

        @stack('meta')

        @bagistoVite(['src/Resources/assets/css/app.css', 'src/Resources/assets/js/app.js'])

        <link
            href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap"
            rel="stylesheet"
        />

        <link
            href="https://fonts.googleapis.com/css2?family=DM+Serif+Display&display=swap"
            rel="stylesheet"
        />

        <link rel="preload" as="image" href="{{ url('cache/logo/bagisto.png') }}">

        @if ($favicon = core()->getConfigData('general.design.admin_logo.favicon'))
            <link
                type="image/x-icon"
                href="{{ Storage::url($favicon) }}"
                rel="shortcut icon"
                sizes="16x16"
            >
        @else
            <link
                type="image/x-icon"
                href="{{ bagisto_asset('images/favicon.ico') }}"
                rel="shortcut icon"
                sizes="16x16"
            />
        @endif

        @stack('styles')

        <style>
            {!! core()->getConfigData('general.content.custom_scripts.custom_css') !!}
        </style>

        {!! view_render_event('bagisto.admin.layout.head') !!}
    </head>

    <body class="h-full dark:bg-gray-950">
        {!! view_render_event('bagisto.admin.layout.body.before') !!}

        <div id="app" class="h-full">
            <!-- Flash Message Blade Component -->
            <x-admin::flash-group />

            <!-- Confirm Modal Blade Component -->
            <x-admin::modal.confirm />

            {!! view_render_event('bagisto.admin.layout.content.before') !!}

            <!-- Page Header Blade Component -->
            <x-admin::layouts.header />

            <div
                class="flex gap-4 group/container {{ (request()->cookie('sidebar_collapsed') ?? 0) ? 'sidebar-collapsed' : 'sidebar-not-collapsed' }}"
                ref="appLayout"
            >
                <!-- Page Sidebar Blade Component -->
                <x-admin::layouts.sidebar />

                <div class="flex-1 max-w-full px-4 pt-3 pb-6 bg-white dark:bg-gray-950 ltr:pl-[286px] rtl:pr-[286px] max-lg:!px-4 transition-all duration-300 group-[.sidebar-collapsed]/container:ltr:pl-[85px] group-[.sidebar-collapsed]/container:rtl:pr-[85px]">
                    <!-- Added dynamic tabs for third level menus  -->
                    <!-- Todo @suraj-webkul need to optimize below statement. -->
                    @if (! request()->routeIs('admin.configuration.index'))
                        <x-admin::layouts.tabs />
                    @endif

                    <!-- Page Content Blade Component -->
                    {{ $slot }}
                </div>
            </div>

            {!! view_render_event('bagisto.admin.layout.content.after') !!}
        </div>

        {!! view_render_event('bagisto.admin.layout.body.after') !!}

        @stack('scripts')
    </body>
</html>
