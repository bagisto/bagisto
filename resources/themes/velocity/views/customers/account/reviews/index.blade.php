@extends('shop::customers.account.index')

@section('page_title')
    {{ __('shop::app.customer.account.review.index.page-title') }}
@endsection

@section('page-detail-wrapper')
    <div class="reviews-head mb20">
        <span class="back-icon">
            <a href="{{ route('customer.account.index') }}">
                <i class="icon icon-menu-back"></i>
            </a>
        </span>

        <span class="account-heading">{{ __('shop::app.customer.account.review.index.title') }}</span>

        @if (count($reviews) > 1)
            <div class="account-action float-right">
                <a href="{{ route('customer.review.deleteall') }}" class="theme-btn light unset">
                    {{ __('shop::app.customer.account.wishlist.deleteall') }}
                </a>
            </div>
        @endif
    </div>

    {!! view_render_event('bagisto.shop.customers.account.reviews.list.before', ['reviews' => $reviews]) !!}

    <div class="reviews-container">
        @if (! $reviews->isEmpty())
            @foreach ($reviews as $review)
                <div class="row col-12 fs16">
                    <div class="col-12 row">
                        @php
                            $image = productimage()->getProductBaseImage($review->product);
                        @endphp

                        <a
                            href="{{ url()->to('/').'/'.$review->product->url_key }}"
                            title="{{ $review->product->name }}"
                            class="col-2 max-sm-img-dimention no-padding">
                            <img class="media" src="{{ $image['small_image_url'] }}" alt=""/>
                        </a>

                        <div class="col-8">
                            <div class="product-name">
                                <a
                                    class="remove-decoration"
                                    href="{{ url()->to('/').'/'.$review->product->url_key }}"
                                    title="{{ $review->product->name }}">
                                    {{$review->product->name}}
                                </a>
                            </div>

                            <star-ratings ratings="{{ $review->rating }}"></star-ratings>

                            <h5 class="fw6">{{ $review->title }}</h5>

                            <p>{{ $review->comment }}</p>
                        </div>

                        <div class="col-2">
                            <a class="unset" href="{{ route('customer.review.delete', $review->id) }}">
                                <span class="rango-delete fs24"></span>
                                <span class="align-vertical-top">{{ __('shop::app.checkout.cart.remove') }}</span>
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach

            <div class="bottom-toolbar">
                {{ $reviews->links()  }}
            </div>
            {{-- <load-more-btn></load-more-btn> --}}
        @else
            <div class="fs16">
                {{ __('customer::app.reviews.empty') }}
            </div>
        @endif

    </div>

    {!! view_render_event('bagisto.shop.customers.account.reviews.list.after', ['reviews' => $reviews]) !!}
@endsection

@push('scripts')
    <script type="text/x-template" id="load-more-template">
        <div class="col-12 row justify-content-center">
            <button type="button" class="theme-btn light" @click="loadNextPage">Load More</button>
        </div>
    </script>

    <script type="text/javascript">
        (() => {
            Vue.component('load-more-btn', {
                template: '#load-more-template',

                methods: {
                    'loadNextPage': function () {
                        let splitedParamsObject = {};

                        let searchedString = window.location.search;
                        searchedString = searchedString.replace('?', '');

                        let splitedParams = searchedString.split('&');

                        splitedParams.forEach(value => {
                            let splitedValue = value.split('=');
                            splitedParamsObject[splitedValue[0]] = splitedValue[1];
                        });

                        splitedParamsObject[page]
                    }
                }
            })
        })()
    </script>
@endpush