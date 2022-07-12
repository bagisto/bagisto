@extends('shop::layouts.master')

@section('page_title')
    {{ __('shop::app.reviews.add-review-page-title') }} - {{ $product->name }}
@endsection

@section('content-wrapper')

    <div class="container">
        <section class="row review-page-container">
            @include ('shop::products.view.small-view', ['product' => $product])

            <div class="col-lg-6 col-md-12">
                <div class="row customer-rating col-12 remove-padding-margin">
                    <h2 class="full-width">
                        {{ __('shop::app.reviews.write-review') }}
                    </h2>

                    <form
                        method="POST"
                        class="review-form"
                        @submit.prevent="onSubmit"
                        action="{{ route('shop.reviews.store', $product->product_id ) }}"
                        enctype="multipart/form-data">

                        @csrf

                        <div :class="`${errors.has('rating') ? 'has-error' : ''}`">
                            <label for="title" class="required">
                                {{ __('admin::app.customers.reviews.rating') }}
                            </label>
                            <star-ratings ratings="5" size="24" editable="true"></star-ratings>
                            <span :class="`control-error ${errors.has('rating') ? '' : 'hide'}`" v-if="errors.has('rating')" v-text="errors.first('rating')"></span>
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

                            <span :class="`control-error ${errors.has('title') ? '' : 'hide'}`" v-text="errors.first('title')"></span>
                        </div>

                        @if (
                            core()->getConfigData('catalog.products.review.guest_review')
                            && ! auth()->guard('customer')->user()
                        )
                            <div :class="`${errors.has('name') ? 'has-error' : ''}`">
                                <label for="title" class="required">
                                    {{ __('shop::app.reviews.name') }}
                                </label>
                                <input  type="text" class="control" name="name" v-validate="'required'" value="{{ old('name') }}">
                                <span :class="`control-error ${errors.has('name') ? '' : 'hide'}`" v-text="errors.first('name')"></span>
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
                            <span :class="`control-error ${errors.has('comment') ? '' : 'hide'}`" v-text="errors.first('comment')"></span>
                        </div>

                        <div class="{!! $errors->has('images.*') ? 'has-error' : '' !!}">
                            <label>{{ __('admin::app.catalog.categories.image') }}</label>

                            <image-wrapper></image-wrapper>

                            <span class="control-error" v-if="{!! $errors->has('images.*') !!}">
                                @php $count=1 @endphp
                                @foreach ($errors->get('images.*') as $key => $message)
                                    @php echo str_replace($key, 'Image'.$count, $message[0]); $count++ @endphp
                                @endforeach
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
                    'addClass' => 'col-lg-3 col-md-12'
                ])
            @endif
        </section>
    </div>

@endsection

@push('scripts')
    <script type="text/javascript" src="{{ asset('vendor/webkul/ui/assets/js/ui.js') }}"></script>
@endpush