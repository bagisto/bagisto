@extends('shop::layouts.master')

@section('page_title')
    {{ $product->meta_title ?? $product->name }}
@stop

@section('seo')
    <meta name="description" content="{{ $product->meta_description }}"/>
    <meta name="description" content="{{ $product->meta_keywords }}"/>
@stop

@section('content-wrapper')
    <section class="product-detail">

        <div class="layouter">
            <product-view>
                <div class="form-container">
                    @csrf()

                    <input type="hidden" name="product" value="{{ $product->id }}">

                    @include ('shop::products.view.gallery')

                    <div class="details">

                        <div class="product-heading">
                            <span>{{ $product->name }}</span>
                        </div>

                        @include ('shop::products.review', ['product' => $product])

                        @include ('shop::products.price', ['product' => $product])

                        @include ('shop::products.view.stock', ['product' => $product])

                        <div class="description">
                            {!! $product->short_description !!}
                        </div>

                        <div class="quantity control-group" :class="[errors.has('quantity') ? 'has-error' : '']">

                            <label class="reqiured">{{ __('shop::app.products.quantity') }}</label>

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
                                    {!! $product->description !!}
                                </div>
                            </div>
                        </accordian>

                        @include ('shop::products.view.attributes')

                        @include ('shop::products.view.reviews')
                    </div>
                </div>
            </product-view>
        </div>

        @include ('shop::products.view.up-sells')

    </section>
@endsection

@push('scripts')

    <script type="text/x-template" id="product-view-template">
        <form method="POST" action="{{ route('cart.add', $product->id) }}" @click.prevent="onSubmit($event)">

            <slot></slot>

        </form>
    </script>

    <script>

        Vue.component('product-view', {

            template: '#product-view-template',

            inject: ['$validator'],

            methods: {
                onSubmit (e) {
                    if(e.target.getAttribute('type') != 'submit')
                        return;

                    this.$validator.validateAll().then(result => {
                        if (result) {
                            if(e.target.getAttribute('data-href')) {
                                window.location.href = e.target.getAttribute('data-href');
                            } else {
                                e.target.submit();
                            }
                        }
                    });
                }
            }
        });

        document.onreadystatechange = function () {
            var state = document.readyState
            var galleryTemplate = document.getElementById('product-gallery-template');
            var addTOButton = document.getElementsByClassName('add-to-buttons')[0];

            if(galleryTemplate){
                if (state != 'interactive') {
                    document.getElementById('loader').style.display="none";
                    addTOButton.style.display="flex";
                }
            }
        }

        window.onload = function() {

            var thumbList = document.getElementsByClassName('thumb-list')[0];
            var thumbFrame = document.getElementsByClassName('thumb-frame');
            var productHeroImage = document.getElementsByClassName('product-hero-image')[0];

            if(thumbList && productHeroImage){

                for(let i=0 ; i < thumbFrame.length ; i++){
                    thumbFrame[i].style.height = (productHeroImage.offsetHeight/4) + "px";
                    thumbFrame[i].style.width = (productHeroImage.offsetHeight/4)+ "px";
                }

                thumbList.style.width = (productHeroImage.offsetHeight/4) + "px";
                thumbList.style.minWidth = (productHeroImage.offsetHeight/4) + "px";
                thumbList.style.height = productHeroImage.offsetHeight + "px";
            }

            window.onresize = function() {
                if(thumbList && productHeroImage){

                    for(let i=0 ; i < thumbFrame.length ; i++){
                        thumbFrame[i].style.height = (productHeroImage.offsetHeight/4) + "px";
                        thumbFrame[i].style.width = (productHeroImage.offsetHeight/4)+ "px";
                    }

                    thumbList.style.width = (productHeroImage.offsetHeight/4) + "px";
                    thumbList.style.minWidth = (productHeroImage.offsetHeight/4) + "px";
                    thumbList.style.height = productHeroImage.offsetHeight + "px";
                }
            }

        };

    </script>


@endpush




