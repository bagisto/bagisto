@extends('shop::layouts.master')
@section('page_title')
    {{ __('shop::app.reviews.add-review-page-title') }} - {{ $product->name }}
@endsection
@section('content-wrapper')
    <section class="review">

        {{-- <div class="category-breadcrumbs">
            <span class="breadcrumb">Home</span> > <span class="breadcrumb">Men</span> > <span class="breadcrumb">Slit Open Jeans</span>
        </div> --}}

        <div class="review-layouter mb-20">

            <div class="product-info">

                <div class="product-image">
                    <img src="{{ bagisto_asset('images/jeans_big.jpg') }}" />
                </div>

                <div class="product-name mt-20">
                    <span>{{ $product->name }}</span>
                </div>

                <div class="product-price mt-10">
                    @inject ('priceHelper', 'Webkul\Product\Helpers\Price')

                    @if ($product->type == 'configurable')
                        <span class="pro-price">{{ core()->currency($priceHelper->getMinimalPrice($product)) }}</span>
                    @else
                        @if ($priceHelper->haveSpecialPrice($product))
                            <span class="pro-price">{{ core()->currency($priceHelper->getSpecialPrice($product)) }}</span>
                        @else
                            <span class="pro-price">{{ core()->currency($product->price) }}</span>
                        @endif
                    @endif

                    {{--  <span class="pro-price-not">
                        <strike> $45.00 </strike>
                    </span>

                    <span class="offer"> 10% Off </span>  --}}
                </div>
            </div>

            <div class="review-form">
                <form method="POST" action="{{ route('shop.reviews.store', $product->id ) }}">
                    @csrf
                    <div class="heading mt-10">
                        <span>{{ __('shop::app.reviews.write-review') }}</span>
                    </div>

                    <div class="rating mt-20">
                        <div class="rating-title"> {{ __('admin::app.customers.reviews.rating') }} </div>

                        <label class="star star-5" for="star-5" onclick="calculateRating(id)" id="1"></label>

                        <label class="star star-4" for="star-4" onclick="calculateRating(id)" id="2"></label>

                        <label class="star star-3" for="star-3" onclick="calculateRating(id)" id="3"></label>

                        <label class="star star-2" for="star-2" onclick="calculateRating(id)" id="4"></label>

                        <label class="star star-1" for="star-1" onclick="calculateRating(id)" id="5"></label>

                        <input type="hidden" id="rating" name="rating">
                    </div>

                    <div>
                        <input type="name" name="title" class="form-control" placeholder="{{ __('shop::app.reviews.review-title') }}">
                    </div>

                    <div class="write-review mt-20">
                        <div class="control-group">
                            <label for="review">{{ __('admin::app.customers.reviews.comment') }}</label>
                            <textarea name="comment">
                            </textarea>
                        </div>
                    </div>

                    <a type="submit" class="btn btn-lg btn-primary">
                        {{ __('shop::app.reviews.submit') }}
                    </a>

                </form>
            </div>

        </div>
    </section>
@endsection


@push('scripts')
<script>

    function calculateRating(id){

        var a=document.getElementById(id);
        document.getElementById("rating").value = id;

        for (let i=1 ; i <= 5 ; i++){

            if(id >= i){
                document.getElementById(i).style.color="#242424";
            }else{
                document.getElementById(i).style.color="#d4d4d4";
            }
        }
    }

</script>
@endpush
