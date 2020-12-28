<link rel="stylesheet" href="{{ asset('themes/velocity/assets/css/bootstrap.min.css') }}" />

@if (core()->getCurrentLocale() && core()->getCurrentLocale()->direction == 'rtl')
    <link href="{{ asset('themes/velocity/assets/css/bootstrap-flipped.css') }}" rel="stylesheet">
@endif

<link rel="stylesheet" href="{{ asset('themes/velocity/assets/css/velocity.css') }}" />

@stack('css')

<style>
    {!! core()->getConfigData('general.content.custom_scripts.custom_css') !!}
</style>