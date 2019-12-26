@inject ('productImageHelper', 'Webkul\Product\Helpers\ProductImage')

@extends('shop::layouts.master')

@section('page_title')
    {{ __('shop::app.reviews.add-review-page-title') }}
@endsection

@section('content-wrapper')
    <create-review></create-review>
@endsection

@push('scripts')
    <script type="text/x-template" id="create-review-template">
        <div class="row review-page-container">

            @include ('shop::products.view.small-view', ['product' => $product])

            <div class="col-6">
                <div class="row customer-rating">
                    <h2 class="full-width">Reviews</h2>

                    <form
                        method="POST"
                        class="review-form"
                        action="{{ route('shop.reviews.store', $product->product_id ) }}"
                        @submit.prevent="onSubmit">

                        @csrf

                        <div class="ratings">
                            <label>Rating*</label>
                            <star-ratings ratings="5" size="24" editable="true"></star-ratings>
                        </div>

                        <div class="title">
                            <label>Title*</label>
                            <input type="text" placeholder="Title" name="title" />
                        </div>

                        <div class="comment">
                            <label>Rating*</label>
                            <textarea rows="4" placeholder="Your Comment" name="comment"></textarea>
                        </div>

                        <div class="submit-btn">
                            <button class="theme-btn fs16" type="submit">{{ __('velocity::app.products.submit-review') }}</button>
                        </div>
                    </form>
                </div>
            </div>

            @include ('shop::products.list.recently-viewed')
        </div>
    </script>

    <script type="text/javascript">
        (() => {
            Vue.component('create-review', {
                template: '#create-review-template',

                data: function () {
                    return {}
                }
            })
        })()
    </script>
@endpush
