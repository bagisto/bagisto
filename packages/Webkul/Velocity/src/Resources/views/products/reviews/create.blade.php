@inject ('productImageHelper', 'Webkul\Product\Helpers\ProductImage')

@extends('shop::layouts.master')

@section('page_title')
    {{ __('shop::app.reviews.add-review-page-title') }} - {{ $product->name }}
@endsection

@section('content-wrapper')

    <div class="container">
        <section class="row review-page-container">
            @include ('shop::products.view.small-view', ['product' => $product])

            <div class="col-6">
                <div class="row customer-rating">
                    <h2 class="full-width">
                        {{ __('shop::app.reviews.write-review') }}
                    </h2>

                    <form
                        method="POST"
                        class="review-form"
                        @submit.prevent="onSubmit"
                        action="{{ route('shop.reviews.store', $product->product_id ) }}">

                        @csrf

                        <div :class="`${errors.has('rating') ? 'has-error' : ''}`">
                            <label for="title" class="required">
                                {{ __('admin::app.customers.reviews.rating') }}
                            </label>
                            <star-ratings ratings="5" size="24" editable="true"></star-ratings>
                            <span :class="`control-error ${errors.has('rating') ? '' : 'hide'}`" v-if="errors.has('rating')">
                                @{{ errors.first('rating') }}
                            </span>
                        </div>

                        <div :class="`${errors.has('title') ? 'has-error' : ''}`">
                            <label for="title" class="required">
                                {{ __('shop::app.reviews.title') }}
                            </label>
                            <input
                                type="text"
                                name="title"
                                class="control"
                                v-validate="'required'"
                                value="{{ old('title') }}" />

                            <span :class="`control-error ${errors.has('title') ? '' : 'hide'}`">
                                @{{ errors.first('title') }}
                            </span>
                        </div>

                        @if (core()->getConfigData('catalog.products.review.guest_review') && ! auth()->guard('customer')->user())
                            <div :class="`${errors.has('name') ? 'has-error' : ''}`">
                                <label for="title" class="required">
                                    {{ __('shop::app.reviews.name') }}
                                </label>
                                <input  type="text" class="control" name="name" v-validate="'required'" value="{{ old('name') }}">
                                <span :class="`control-error ${errors.has('name') ? '' : 'hide'}`">
                                    @{{ errors.first('name') }}
                                </span>
                            </div>
                        @endif

                        <div :class="`${errors.has('comment') ? 'has-error' : ''}`">
                            <label for="comment" class="required">
                                {{ __('admin::app.customers.reviews.comment') }}
                            </label>
                            <textarea
                                type="text"
                                class="control"
                                name="comment"
                                v-validate="'required'"
                                value="{{ old('comment') }}">
                            </textarea>
                            <span :class="`control-error ${errors.has('comment') ? '' : 'hide'}`">
                                @{{ errors.first('comment') }}
                            </span>
                        </div>

                        <div class="submit-btn">
                            <button
                                type="submit"
                                class="theme-btn fs16">
                                {{ __('velocity::app.products.submit-review') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            @if ($showRecentlyViewed)
                @include ('shop::products.list.recently-viewed', [
                    'addClass' => 'col-3'
                ])
            @endif
        </section>
    </div>

@endsection
