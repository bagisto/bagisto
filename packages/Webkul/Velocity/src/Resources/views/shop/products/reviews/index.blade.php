@extends('shop::layouts.master')

@section('page_title')
    {{ __('shop::app.reviews.product-review-page-title') }}
@endsection

@php
    $ratings = [
        '', '', '', ''
    ];

    $ratings = [
        10, 30, 20, 15, 25
    ];

    $totalReviews = 25;
    $totalRatings = array_sum($ratings);

@endphp

@push('css')
    <style>
        .reviews {
            display: none !important;
        }
    </style>
@endpush

@section('content-wrapper')
    <div class="container">
        <div class="row review-page-container">
            @include ('shop::products.view.small-view', ['product' => $product])

            <div class="col-lg-7 col-md-12 fs16">
                <h2 class="full-width mb30">Rating and Reviews</h2>

                @include ('shop::products.view.reviews')
            </div>
        </div>
    </div>
@endsection