@extends('shop::layouts.master')

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

@push('css')
    @if (! empty($sliderData))
        <link rel="preload" as="image" href="{{ Storage::url($sliderData[0]['path']) }}">
    @else
        <link rel="preload" as="image" href="{{ asset('/themes/velocity/assets/images/banner.webp') }}">
    @endif

    <style type="text/css">
        .product-price span:first-child, .product-price span:last-child {
            font-size: 18px;
            font-weight: 600;
        }
    </style>
@endpush

@section('content-wrapper')
    @include('shop::home.slider')
@endsection

@section('full-content-wrapper')

    <div class="full-content-wrapper">
        {!! view_render_event('bagisto.shop.home.content.before') !!}

            @if ($velocityMetaData)
                {!! DbView::make($velocityMetaData)->field('home_page_content')->render() !!}
            @else
                @include('shop::home.advertisements.advertisement-four')
                @include('shop::home.featured-products')
                @include('shop::home.advertisements.advertisement-three')
                @include('shop::home.new-products')
                @include('shop::home.advertisements.advertisement-two')
            @endif

        {{ view_render_event('bagisto.shop.home.content.after') }}
    </div>

@endsection

