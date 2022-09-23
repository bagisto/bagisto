@extends('shop::customers.account.index')

@section('page_title')
    {{ __('shop::app.customer.account.review.index.page-title') }}
@endsection

@section('page-detail-wrapper')
    <div class="reviews-head mb20">
        <span class="account-heading">{{ __('shop::app.customer.account.review.index.title') }}</span>

        @if (count($reviews) > 1)
            <div class="account-action float-right">
                <form id="deleteAllReviewForm" action="{{ route('shop.customer.review.delete_all') }}" method="post">
                    @method('delete')
                    @csrf
                </form>

                <a href="javascript:void(0);" class="theme-btn light unset" onclick="confirm('{{ __('shop::app.customer.account.review.delete-all.confirmation-message') }}') ? document.getElementById('deleteAllReviewForm').submit() : null;">
                    {{ __('shop::app.customer.account.review.delete-all.title') }}
                </a>
            </div>
        @endif
    </div>

    {!! view_render_event('bagisto.shop.customers.account.reviews.list.before', ['reviews' => $reviews]) !!}

    <div class="reviews-container">
        @if (! $reviews->isEmpty())
            @foreach ($reviews as $review)
                <div class="row col-12 lg-card-container list-card product-card">
                    <div class="product-image">
                        @php
                            $image = product_image()->getProductBaseImage($review->product);
                        @endphp

                        <a
                            title="{{ $review->product->name }}"
                            href="{{ url()->to('/').'/'.$review->product->url_key }}">
                            <img src="{{ $image['small_image_url'] }}" title="{{ $review->product->name }}">
                        </a>
                    </div>

                    <div class="product-information p-2">
                        <div class="d-flex justify-content-between">
                            <div class="product-name">
                                <a
                                    href="{{ url()->to('/').'/'.$review->product->url_key }}"
                                    title="{{ $review->product->name }}" class="unset">

                                    <span class="fs16">{{ $review->product->name }}</span>
                                </a>

                                <star-ratings ratings="{{ $review->rating }}"></star-ratings>

                                <h5 class="fw6" v-pre>{{ $review->title }}</h5>

                                <p v-pre>{{ $review->comment }}</p>
                            </div>  

                            <div>
                                <form id="deleteReviewForm{{ $review->id }}" action="{{ route('shop.customer.review.delete', $review->id) }}" method="post">
                                    @method('delete')

                                    @csrf
                                </form>

                                <a class="unset" href="javascript:void(0);" onclick="deleteReview('{{ $review->id }}')">
                                    <span class="rango-delete fs24"></span>
                                    
                                    <span class="align-vertical-top">{{ __('shop::app.checkout.cart.remove') }}</span>
                                </a>
                            </div>                      
                        </div>
                    </div>
                </div>               
            @endforeach

            <div class="bottom-toolbar">
                {{ $reviews->links()  }}
            </div>
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
            });
        })();

        function deleteReview(reviewId) {
            if (! confirm('{{ __("shop::app.customer.account.review.delete.confirmation-message") }}')) {
                return;
            }

            $(`#deleteReviewForm${reviewId}`).submit();
        }
    </script>
@endpush
