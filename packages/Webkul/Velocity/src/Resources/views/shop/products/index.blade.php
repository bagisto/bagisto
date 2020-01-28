@inject ('toolbarHelper', 'Webkul\Product\Helpers\Toolbar')
@inject ('productRepository', 'Webkul\Product\Repositories\ProductRepository')

@extends('shop::layouts.master')

@section('page_title')
    {{ $category->meta_title ?? $category->name }}
@stop

@section('seo')
    <meta name="description" content="{{ $category->meta_description }}" />
    <meta name="keywords" content="{{ $category->meta_keywords }}" />
@stop

@push('css')
    <style type="text/css">
        @media only screen and (max-width: 992px) {
            .main-content-wrapper .vc-header {
                box-shadow: unset;
            }
        }
    </style>
@endpush

@php
    $isDisplayMode = in_array(
        $category->display_mode, [
            null,
            'products_only',
            'products_and_description'
        ]
    );

    $products = $productRepository->getAll($category->id);
@endphp

@section('content-wrapper')
    <section class="row col-12 velocity-divide-page category-page-wrapper">
        {!! view_render_event('bagisto.shop.productOrCategory.index.before', ['category' => $category]) !!}

        @if (in_array($category->display_mode, [null, 'products_only', 'products_and_description']))
            @include ('shop::products.list.layered-navigation')
        @endif

        <div class="category-container right">
            <div class="row remove-padding-margin">
                <div class="pl0 col-12">
                    <h1 class="fw6 mb10">{{ $category->name }}</h1>

                    @if ($isDisplayMode && $products->count())
                        @if ($category->description)
                            <div class="category-description">
                                {!! $category->description !!}
                            </div>
                        @endif
                    @endif
                </div>

                {{-- <div class="col-6">
                    <div class="hero-image mb-35">
                        @if (!is_null($category->image))
                            <img class="logo" src="{{ $category->image_url }}" />
                        @endif
                    </div>
                </div> --}}
            </div>

            <div class="filters-container" v-if="isMobile()">
                @include ('shop::products.list.toolbar')
            </div>

            <div
                class="category-block"
                @if ($category->display_mode == 'description_only')
                    style="width: 100%"
                @endif>

                <div class="hero-image mb-35">
                    @if (!is_null($category->image))
                        {{-- <img class="logo" src="{{ $category->image_url }}" /> --}}
                    @endif
                </div>

                @if ($isDisplayMode)
                    @if ($products->count())
                        @if ($toolbarHelper->getCurrentMode() == 'grid')
                            <div class="row col-12 remove-padding-margin">
                                @foreach ($products as $productFlat)
                                    @include ('shop::products.list.card', ['product' => $productFlat])
                                @endforeach
                            </div>
                        @else
                            <div class="product-list">
                                @foreach ($products as $productFlat)
                                    @include ('shop::products.list.card', [
                                        'list' => true,
                                        'product' => $productFlat
                                    ])
                                @endforeach
                            </div>
                        @endif

                        {!! view_render_event('bagisto.shop.productOrCategory.index.pagination.before', ['category' => $category]) !!}

                        <div class="bottom-toolbar">
                            {{ $products->appends(request()->input())->links() }}
                        </div>

                        {!! view_render_event('bagisto.shop.productOrCategory.index.pagination.after', ['category' => $category]) !!}

                    @else
                        <div class="product-list empty">
                            <h2>{{ __('shop::app.products.whoops') }}</h2>

                            <p>
                                {{ __('shop::app.products.empty') }}
                            </p>
                        </div>

                    @endif
                @endif
            </div>
        </div>

        {!! view_render_event('bagisto.shop.productOrCategory.index.after', ['category' => $category]) !!}
    </section>
@stop