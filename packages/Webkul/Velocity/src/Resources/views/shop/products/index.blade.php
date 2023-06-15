@inject ('toolbarHelper', 'Webkul\Product\Helpers\Toolbar')
@inject ('productRepository', 'Webkul\Product\Repositories\ProductRepository')

@extends('shop::layouts.master')

@section('page_title')
    {{ trim($category->meta_title) != "" ? $category->meta_title : $category->name }}
@stop

@section('seo')
    <meta name="description" content="{{ $category->meta_description }}" />
    <meta name="keywords" content="{{ $category->meta_keywords }}" />

    @if (core()->getConfigData('catalog.rich_snippets.categories.enable'))
        <script type="application/ld+json">
            {!! app('Webkul\Product\Helpers\SEO')->getCategoryJsonLd($category) !!}
        </script>
    @endif
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
    $isProductsDisplayMode = in_array(
        $category->display_mode, [
            null,
            'products_only',
            'products_and_description'
        ]
    );

    $isDescriptionDisplayMode = in_array(
        $category->display_mode, [
            null,
            'description_only',
            'products_and_description'
        ]
    );
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
                        <h2 class="fw6 mb10">{{ $category->name }}</h2>

                        @if ($isDescriptionDisplayMode)
                            @if ($category->description)
                                <div class="category-description">
                                    {!! $category->description !!}
                                </div>
                            @endif
                        @endif
                    </div>

                    <div class="col-12 no-padding">
                        <div class="hero-image">
                            @if (!is_null($category->category_banner))
                                <img class="logo" src="{{ $category->banner_url }}" alt="" width="100%" height="350px" />
                            @endif
                        </div>
                    </div>

                    <div class='col-md-12'>
                        <div class='carousel-products sub-category'>
                            <carousel-component
                                :slides-per-page="slidesPerPage"
                                pagination-enabled="hide"
                                :slides-count="{{count($childCategory)}}">

                                @foreach ($childCategory as $index => $childSubCategory)
                                    <slide slot="slide-{{ $index }}">
                                        <div class='childSubCategory'>
                                            <a href='{{ url($childSubCategory->url_path) }}'>
                                                <div>
                                                    <img src='{{ $childSubCategory->getImageUrlAttribute()?? url("/vendor/webkul/ui/assets/images/product/small-product-placeholder.png") }}'>
                                                    <label>{{ $childSubCategory->name }}</label>
                                                </div>
                                            </a>
                                        </div>
                                    </slide>
                                @endforeach
                            </carousel-component>
                        </div>
                    </div>
                </div>

                @if ($isProductsDisplayMode)
                    <div class="filters-container">
                        <template v-if="products.length >= 0">
                            @include ('shop::products.list.toolbar')
                        </template>
                    </div>

                    <div
                        class="category-block"
                        @if ($category->display_mode == 'description_only')
                            style="width: 100%"
                        @endif>

                        <shimmer-component v-if="isLoading" shimmer-count="4"></shimmer-component>

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

                            <div class="bottom-toolbar" v-html="paginationHTML"></div>

                            {!! view_render_event('bagisto.shop.productOrCategory.index.pagination.after', ['category' => $category]) !!}
                        </template>

                        <div class="product-list empty" v-else>
                            <h2>{{ __('shop::app.products.whoops') }}</h2>
                            <p>{{ __('shop::app.products.empty') }}</p>
                        </div>
                    </div>
                @endif
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
                    'paginationHTML': '',
                    'currentScreen': window.innerWidth,
                    'slidesPerPage': 5,
                }
            },

            created: function () {
                this.getCategoryProducts();
                this.setSlidesPerPage(this.currentScreen);
            },

            methods: {
                'getCategoryProducts': function () {
                    this.$http.get(`${this.$root.baseUrl}/category-products/{{ $category->id }}${window.location.search}`)
                    .then(response => {
                        this.isLoading = false;
                        this.products = response.data.products;
                        this.paginationHTML = response.data.paginationHTML;
                    })
                    .catch(error => {
                        this.isLoading = false;
                        console.log(this.__('error.something_went_wrong'));
                    })
                },

                setSlidesPerPage: function (width) {
                    if (width >= 1200) {
                        this.slidesPerPage = 5;
                    } else if (width < 1200 && width >= 626) {
                        this.slidesPerPage = 3;
                    } else if (width < 626 && width >= 400) {
                        this.slidesPerPage = 2;
                    } else {
                        this.slidesPerPage = 1;
                    }
                }
            }
        })
    </script>
@endpush