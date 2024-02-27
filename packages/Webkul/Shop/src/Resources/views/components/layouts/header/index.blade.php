{!! view_render_event('bagisto.shop.layout.header.before') !!}

<div class="max-lg:hidden">
    <x-shop::layouts.header.desktop.top />
</div>

<header class="sticky top-0 z-10 shadow-sm shadow-gray bg-white max-lg:shadow-none">
    <x-shop::layouts.header.desktop />

    <x-shop::layouts.header.mobile />
</header>

{!! view_render_event('bagisto.shop.layout.header.after') !!}
