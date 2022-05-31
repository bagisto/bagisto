@extends('shop::customers.account.index')

@section('page_title')
    {{ __('shop::app.customer.account.review.index.page-title') }}
@endsection

@section('account-content')
    <div class="account-layout">
        <div class="account-head">
            <span class="back-icon"><a href="{{ route('customer.profile.index') }}"><i class="icon icon-menu-back"></i></a></span>

            <span class="account-heading">{{ __('shop::app.customer.account.review.index.title') }}</span>

            @if (count($reviews) > 1)
                <div class="account-action">
                    <form id="deleteAllReviewForm" action="{{ route('customer.review.deleteall') }}" method="post">
                        @method('delete')

                        @csrf
                    </form>

                    <a href="javascript:void(0);" onclick="confirm('{{ __('shop::app.customer.account.review.delete-all.confirmation-message') }}') ? document.getElementById('deleteAllReviewForm').submit() : null;">
                        {{ __('shop::app.customer.account.review.delete-all.title') }}
                    </a>
                </div>
            @endif

            <span></span>
            
            <div class="horizontal-rule"></div>
        </div>

        {!! view_render_event('bagisto.shop.customers.account.reviews.list.before', ['reviews' => $reviews]) !!}

        <div class="account-items-list">
            @if (! $reviews->isEmpty())
                @foreach ($reviews as $review)
                    <div class="account-item-card mt-15 mb-15">
                        <div class="media-info">
                            @php $image = productimage()->getProductBaseImage($review->product); @endphp

                            <a href="{{ route('shop.productOrCategory.index', $review->product->url_key) }}" title="{{ $review->product->name }}">
                                <img class="media" src="{{ $image['small_image_url'] }}" alt=""/>
                            </a>

                            <div class="info">
                                <div class="product-name">
                                    <a href="{{ route('shop.productOrCategory.index', $review->product->url_key) }}" title="{{ $review->product->name }}">
                                        {{$review->product->name}}
                                    </a>
                                </div>

                                <div class="stars mt-10">
                                    @for($i=0 ; $i < $review->rating ; $i++)
                                        <span class="icon star-icon"></span>
                                    @endfor
                                </div>

                                <div class="mt-10" v-pre>
                                    {{ $review->comment }}
                                </div>
                            </div>
                        </div>

                        <div class="operations">
                            <form id="deleteReviewForm{{ $review->id }}" action="{{ route('customer.review.delete', $review->id) }}" method="post">
                                @method('delete')
                                @csrf
                            </form>

                            <a class="mb-50" href="javascript:void(0);" onclick="deleteReview('{{ $review->id }}')">
                                <span class="icon trash-icon"></span>
                            </a>
                        </div>
                    </div>

                    <div class="horizontal-rule mb-10 mt-10"></div>
                @endforeach

                <div class="bottom-toolbar">
                    {{ $reviews->links()  }}
                </div>
            @else
                <div class="empty mt-15">
                    {{ __('customer::app.reviews.empty') }}
                </div>
            @endif
        </div>

        {!! view_render_event('bagisto.shop.customers.account.reviews.list.after', ['reviews' => $reviews]) !!}
    </div>
@endsection

@push('scripts')
    <script>
        function deleteReview(reviewId) {
            if (! confirm('{{ __("shop::app.customer.account.review.delete.confirmation-message") }}')) {
                return;
            }

            $(`#deleteReviewForm${reviewId}`).submit();
        }
    </script>
@endpush
