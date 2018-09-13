@extends('shop::layouts.master')

@section('slider')
    @include('shop::home.slider')
@endsection

@section('content-wrapper')
    @include('shop::home.featured-products')

    @include('shop::home.new-products')

    @include('shop::home.news-updates')
@endsection
