@extends('shop::layouts.master')

@section('page_title')
    {{ __('shop::app.home.page-title') }}
@endsection

@section('slider')
    @include('shop::home.slider')
@endsection

@section('content-wrapper')
    @include('shop::home.featured-products')

    @include('shop::home.new-products')
@endsection
