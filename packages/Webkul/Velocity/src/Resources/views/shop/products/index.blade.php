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
        .product-price span:first-child, .product-price span:last-child {
            font-size: 18px;
            font-weight: 600;
        }

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
    <category-component></category-component>
@stop

@push('scripts')
    <script type="text/x-template" id="category-template">
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
    
                    <div class="col-12 no-padding">
                        <div class="hero-image">
                            @if (!is_null($category->image))
                                <img class="logo" src="{{ $category->image_url }}" />
                            @endif
                        </div>
                    </div>
                </div>
    
                <div class="filters-container">
                    @include ('shop::products.list.toolbar')
                </div>
    
                <div
                    class="category-block"
                    @if ($category->display_mode == 'description_only')
                        style="width: 100%"
                    @endif>

                    @if ($isDisplayMode)
                        <shimmer-component v-if="isLoading && !isMobile()" shimmer-count="4"></shimmer-component>

                        <template v-else-if="products.length > 0">
                            @if ($toolbarHelper->getCurrentMode() == 'grid')
                                <div class="row col-12 remove-padding-margin">
                                    <product-card
                                        :key="index"
                                        :product="product"
                                        v-for="(product, index) in products">
                                    </product-card>
                                </div>
                            @else
                                <div class="product-list">
                                    <product-card
                                        list=true
                                        :key="index"
                                        :product="product"
                                        v-for="(product, index) in products">
                                    </product-card>
                                </div>
                            @endif
    
                            {!! view_render_event('bagisto.shop.productOrCategory.index.pagination.before', ['category' => $category]) !!}
    
                            <div class="bottom-toolbar">
                                {{ $products->appends(request()->input())->links() }}
                            </div>
    
                            {!! view_render_event('bagisto.shop.productOrCategory.index.pagination.after', ['category' => $category]) !!}
                        </template>
    
                        <div class="product-list empty" v-else>
                            <h2>{{ __('shop::app.products.whoops') }}</h2>
                            <p>{{ __('shop::app.products.empty') }}</p>
                        </div>
                    @endif
                </div>
            </div>
    
            {!! view_render_event('bagisto.shop.productOrCategory.index.after', ['category' => $category]) !!}
        </section>
    </script>

    <script>
        Vue.component('category-component', {
            template: '#category-template',

            data: function () {
                return {
                    'products': [],
                    'isLoading': true,
                }
            },

            created: function () {
                this.getCategoryProducts();
            },

            methods: {
                'getCategoryProducts': function () {
                    this.$http.get(`${this.$root.baseUrl}/category-products/{{ $category->id }}${window.location.search}`)
                    .then(response => {
                        this.isLoading = false;
                        this.products = response.data.products.data;
                    })
                    .catch(error => {
                        this.isLoading = false;
                        console.log(this.__('error.something_went_wrong'));
                    })
                }
            }
        })
    </script>
@endpush