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
            <form method="POST" action="{{ route('cart.add', $product->id) }}">
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

                    <div class="quantity control-group">
                    <label class="reqiured">Quantity</label>
                        <input name="quantity" class="control" value="1" v-validate="'numeric'" required style="width: 60px;">
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


<style>

    .header {
        position: sticky;
        top: 16px;
    }

</style>


@push('scripts')

    <script>

        window.onscroll = function() {scrollFunction()};

        function scrollFunction() {

            var scrollTop = window.pageYOffset

            var elems = document.getElementById("header-bottom");

            if(scrollTop > 200){

                elems.style.display = "none";

            }else {
                elems.style.display = "block";
            }

            console.log(scrollTop);
        }

    </script>

@endpush



