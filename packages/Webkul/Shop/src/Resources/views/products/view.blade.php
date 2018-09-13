@extends('shop::layouts.master')

@section('content-wrapper')
    <section class="product-detail">
        <div class="category-breadcrumbs">

            <span class="breadcrumb">Home</span> > <span class="breadcrumb">Men</span> > <span class="breadcrumb">Slit Open Jeans</span>

        </div>
        <div class="layouter">
            {{-- {{ dd(session()->getId()) }} --}}
            <form method="POST" action="{{ route('cart.add', $product->id) }}">
                @csrf()

                <input type="hidden" name="product">

                @include ('shop::products.view.gallery')

                <div class="details">

                    <div class="product-heading">
                        <span>{{ $product->name }}</span>
                    </div>

                    @include ('shop::products.review', ['product' => $product])

                    @include ('shop::products.price', ['product' => $product])

                    @include ('shop::products.view.stock')


                    <div class="description">
                        {{ $product->short_description }}
                    </div>

                    @include ('shop::products.view.configurable-options')

                    <accordian :title="'{{ __('shop::app.products.description') }}'" :active="true">
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

            </form>

        </div>

        @include ('shop::products.view.up-sells')

    </section>

@endsection
