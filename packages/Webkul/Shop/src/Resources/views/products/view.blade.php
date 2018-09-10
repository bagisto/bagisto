@extends('shop::layouts.master')

@section('content-wrapper')
    <section class="product-detail">
        <div class="category-breadcrumbs">

            <span class="breadcrumb">Home</span> > <span class="breadcrumb">Men</span> > <span class="breadcrumb">Slit Open Jeans</span>

        </div>
        <div class="layouter">

            @include ('shop::products.view.gallery')

            <div class="product-details" id="dealit">

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

    @push('scripts')
        <script type="text/javascript">
            var topBoundOfProductGallery = 0;
            function getTopBound() {
                var rect = document.getElementById("getbound").getBoundingClientRect();
                topBoundOfProductGallery = rect.top;
                console.log('From Top = ', rect.top);
            }

            window.onload = getTopBound;

            // window.onscroll = function() {
            //     myFunction()
            // };

            // $(document).ready(function () {
            //     $(document).scroll(function (event) {
            //         var scroll = $(document).scrollTop();
            //         if(scroll > 182) {
            //             $('#dealit').css('width', '50%');

            //             $('#dealit').css('margin-left', '59.7%');

            //             $('#getbound').css('position', 'fixed');

            //             $('#getbound').css('top', '0');
            //         } else if(scroll < 182) {
            //             $('#dealit').css('width', '100%');

            //             $('#dealit').css('margin-left', '');

            //             $('#getbound').css('position', '');

            //             $('#getbound').css('top', '');

            //         }

            //     });
            // });

            // function myFunction() {

            //     if(document.body.scrollTop > 182 || document.documentElement.scrollTop > 182) {

            //         // document.getElementById('dealit').style.style = 'none';

            //         document.getElementById('dealit').classList.remove("product-details");

            //         document.getElementById('dealit').width = '50%';

            //         document.getElementById('dealit').marginLeft = '60%';

            //         document.getElementById('getbound').style.position = 'fixed';

            //         document.getElementById('getbound').style.top = '0';

            //     }
            //     else if(document.body.scrollTop < 182 || document.documentElement.scrollTop < 182) {

            //         document.getElementById('dealit').classList.add("product-details");

            //         document.getElementById('dealit').width = '100%';

            //         document.getElementById('dealit').marginLeft = '0';

            //         document.getElementById('getbound').style.position = '';

            //         document.getElementById('getbound').style.top = '';
            //     }
            // }
        </script>
    @endpush
@endsection
