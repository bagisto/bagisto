<link rel="stylesheet" href="{{ asset('themes/velocity/assets/css/velocity.css') }}" />
<link rel="stylesheet" href="{{ asset('themes/velocity/assets/css/bootstrap.min.css') }}" />

@if (core()->getCurrentLocale() && core()->getCurrentLocale()->direction == 'rtl')
    <link href="{{ asset('themes/velocity/assets/css/bootstrap-flipped.css') }}" rel="stylesheet">
@endif

@if ($favicon = core()->getCurrentChannel()->favicon_url)
    <link rel="icon" sizes="16x16" href="{{ $favicon }}" />
@else
    <link rel="icon" sizes="16x16" href="{{ asset('/themes/velocity/assets/images/static/v-icon.png') }}" />
@endif

@stack('css')

<style>
    {!! core()->getConfigData('general.content.custom_scripts.custom_css') !!}
</style>