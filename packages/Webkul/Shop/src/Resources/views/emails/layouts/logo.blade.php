@if (core()->getConfigData('general.design.admin_logo.logo_image'))
    <img src="{{ \Illuminate\Support\Facades\Storage::url(core()->getConfigData('general.design.admin_logo.logo_image')) }}" alt="{{ config('app.name') }}" style="height: 40px; width: 110px;"/>
@else
    <img src="{{ bagisto_asset('images/logo.svg') }}">
@endif