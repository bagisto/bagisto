@php
    $count = $velocityMetaData->new_products_count;

    $newProducts = app('Webkul\Velocity\Repositories\Product\ProductRepository')->getNewProducts($count);

    $showRecentlyViewed = false;
@endphp

@if (! empty($newProducts))
    <div class="container-fluid popular-products">

        <card-list-header
            heading="{{ __('shop::app.home.new-products') }}"
            scrollable="new-products-carousel"
        ></card-list-header>

        {!! view_render_event('bagisto.shop.new-products.before') !!}

        <div class="row flex-nowrap">
            @if ($showRecentlyViewed)
                @push('css')
                    <style>
                        .recently-viewed {
                            padding-right: 0px;
                        }
                    </style>
                @endpush

                <div class="col-9 no-padding">
                    <carousel-component
                        :slides-count="{{ sizeof($newProducts) }}"
                        slides-per-page="5"
                        id="new-products-carousel"
                        navigation-enabled="hide"
                        pagination-enabled="hide">

                        @foreach ($newProducts as $index => $product)

                            <slide slot="slide-{{ $index }}">
                                @include ('shop::products.list.card', ['product' => $product])
                            </slide>

                        @endforeach

                    </carousel-component>
                </div>

                @include ('shop::products.list.recently-viewed')
            @else
                <carousel-component
                    :slides-count="{{ sizeof($newProducts) }}"
                    slides-per-page="6"
                    id="new-products-carousel">

                    @foreach ($newProducts as $index => $product)

                        <slide slot="slide-{{ $index }}">
                            @include ('shop::products.list.card', ['product' => $product])
                        </slide>

                    @endforeach

                </carousel-component>
            @endif
        </div>

        {!! view_render_event('bagisto.shop.new-products.after') !!}
    </div>
@endif