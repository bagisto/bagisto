@php
    $count = $velocityMetaData->new_products_count;

    $newProducts = app('Webkul\Velocity\Repositories\Product\ProductRepository')->getNewProducts($count);
@endphp

@if (! empty($newProducts))
    <div class="container-fluid">
        <card-list-header heading="{{ __('shop::app.home.new-products') }}">
        </card-list-header>

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
                            slides-per-page="5"
                            navigation-enabled="hide"
                            pagination-enabled="hide"
                            id="new-products-carousel"
                            :slides-count="{{ sizeof($newProducts) }}">

                            @foreach ($newProducts as $index => $product)
                                <slide slot="slide-{{ $index }}">
                                    @include ('shop::products.list.card', [
                                        'product' => $product,
                                        'addToCartBtnClass' => 'small-padding'
                                    ])
                                </slide>
                            @endforeach

                        </carousel-component>
                    </div>

                    @include ('shop::products.list.recently-viewed', [
                        'addClass' => 'col-3',
                        'addClassWrapper' => 'scrollable max-height-350',
                    ])
                @else
                    <carousel-component
                        slides-per-page="6"
                        navigation-enabled="hide"
                        pagination-enabled="hide"
                        id="new-products-carousel"
                        :slides-count="{{ sizeof($newProducts) }}">

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