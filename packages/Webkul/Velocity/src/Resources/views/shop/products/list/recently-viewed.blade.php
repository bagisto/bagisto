@php
    $direction = core()->getCurrentLocale()->direction;
@endphp

<recently-viewed
    title="{{ __('velocity::app.products.recently-viewed') }}"
    no-data-text="{{ __('velocity::app.products.not-available') }}"
    add-class="{{ isset($addClass) ? $addClass . " $direction": '' }}"
    quantity="{{ $quantity ?? null }}"
    add-class-wrapper="{{ $addClassWrapper ?? '' }}">
</recently-viewed>
