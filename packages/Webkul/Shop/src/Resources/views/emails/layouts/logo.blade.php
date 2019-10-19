@if ($logo = core()->getCurrentChannel()->logo_url)
<img src="{{ $logo }}"/>
@else
    <img src="{{ bagisto_asset('images/logo.svg') }}">
@endif