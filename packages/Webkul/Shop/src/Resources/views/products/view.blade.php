@extends('shop::layouts.master')

@section('content-wrapper')
    <section class="product-detail">
        <div class="category-breadcrumbs">

            <span class="breadcrumb">Home</span> > <span class="breadcrumb">Men</span> > <span class="breadcrumb">Slit Open Jeans</span>

        </div>

        <div class="layouter">

            @include ('shop::products.view.gallery')

            <div class="details">

                <div class="product-heading">
                    <span>{{ $product->name }}</span>
                </div>

                <div class="rating">
                    <img src="{{ bagisto_asset('images/5star.svg') }}" />
                    75 Ratings & 11 Reviews
                </div>

                @include ('shop::products.price', ['product' => $product])

                @include ('shop::products.view.stock')

                <br/>

                <div class="description">
                    {{ $product->short_description }}
                </div>

                @if ($product->type == 'configurable')

                    @include ('shop::products.view.configurable-options')
                
                @endif

                <accordian :title="{{ __('shop::app.products.description') }}" :active="true">
                    <div slot="header">
                        {{ __('shop::app.products.description') }}
                        <i class="icon expand-icon right"></i>
                    </div>

                    <div slot="body">
                        <div class="full-description">
                            {{ $product->description }}
                        </div>
                    </div>
                </accordian>

                @include ('shop::products.view.attributes')

                @include ('shop::products.view.reviews')

            </div>
        </div>

        @include ('shop::products.view.up-sells')

    </section>
@endsection
