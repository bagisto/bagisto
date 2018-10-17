@extends('shop::layouts.master')

@section('page_title')
    {{ $product->name }}
@stop

@section('content-wrapper')
    <section class="product-detail">
        <div class="category-breadcrumbs">
            <span class="breadcrumb">Home</span> > <span class="breadcrumb">Men</span> > <span class="breadcrumb">Slit Open Jeans</span>
        </div>
        <div class="layouter">
            <form method="POST" action="{{ route('cart.add', $product->id) }}" @submit.prevent="onSubmit">
                @csrf()

                <input type="hidden" name="product" value="{{ $product->id }}">

                @include ('shop::products.view.gallery')

                <div class="details">

                    <div class="product-heading">
                        <span>{{ $product->name }}</span>
                    </div>

                    {{-- @include ('shop::products.review', ['product' => $product]) --}}

                    @include ('shop::products.price', ['product' => $product])

                    @include ('shop::products.view.stock')


                    <div class="description">
                        {{ $product->short_description }}
                    </div>

                    <div class="quantity control-group" :class="[errors.has('quantity') ? 'has-error' : '']">

                    <label class="reqiured">Quantity</label>

                        <input name="quantity" class="control" value="1" v-validate="'required|numeric|min_value:1'" style="width: 60px;">

                        <span class="control-error" v-if="errors.has('quantity')">@{{ errors.first('quantity') }}</span>
                    </div>

                    @if ($product->type == 'configurable')
                        <input type="hidden" value="true" name="is_configurable">
                    @else
                        <input type="hidden" value="false" name="is_configurable">
                    @endif

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

@push('scripts')
    <script>
        document.onreadystatechange = function () {
            var state = document.readyState
            var galleryTemplate = document.getElementById('product-gallery-template');
            var addTOButton = document.getElementsByClassName('add-to-buttons')[0];

            if(galleryTemplate){
                if (state == 'interactive') {
                    galleryTemplate.style.display="none";
                } else  {
                    document.getElementById('loader').style.display="none";
                    // addTOButton.style.display="flex";
                }
            }
        }
    </script>
@endpush




