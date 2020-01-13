@extends('shop::layouts.master')
@inject ('productImageHelper', 'Webkul\Product\Helpers\ProductImage')
@inject ('productRatingHelper', 'Webkul\Product\Helpers\Review')

@php
    $channel = core()->getCurrentChannel();

    $homeSEO = $channel->home_seo;

    if (isset($homeSEO)) {
        $homeSEO = json_decode($channel->home_seo);

        $metaTitle = $homeSEO->meta_title;

        $metaDescription = $homeSEO->meta_description;

        $metaKeywords = $homeSEO->meta_keywords;
    }
@endphp

@section('page_title')
    {{ isset($metaTitle) ? $metaTitle : "" }}
@endsection

@section('head')

    @if (isset($homeSEO))
        @isset($metaTitle)
            <meta name="title" content="{{ $metaTitle }}" />
        @endisset

        @isset($metaDescription)
            <meta name="description" content="{{ $metaDescription }}" />
        @endisset

        @isset($metaKeywords)
            <meta name="keywords" content="{{ $metaKeywords }}" />
        @endisset
    @endif
@endsection

@section('content-wrapper')
    @if ($velocityMetaData->slider)
        @include('shop::home.slider')
    @endif
@endsection

@section('full-content-wrapper')

    <div class="full-content-wrapper">
        {!! view_render_event('bagisto.shop.home.content.before') !!}

            {!! DbView::make($velocityMetaData)->field('home_page_content')->render() !!}

        {{ view_render_event('bagisto.shop.home.content.after') }}
    </div>

@endsection

    <!-- offers and sale logos -->
    {{-- @include('shop::home.advertisements.advertisement-one') --}}

    <!-- Show Product Policy -->
    {{-- @include('shop::home.product-policy') --}}

    <!-- advertisement two -->
    {{-- @include('shop::home.advertisements.advertisement-two') --}}

    <!-- hot categories -->
    {{-- @include('shop::home.hot-categories') --}}

    <!-- Advertisement three -->
    {{-- @include('shop::home.advertisements.advertisement-three') --}}

    {{-- currently no customer reviews for the shop --}}

    <!-- show customer reviews -->
    {{-- @include('shop::home.customer-reviews') --}}

    <!-- show popular categories -->
    {{-- @include('shop::home.popular-categories') --}}
