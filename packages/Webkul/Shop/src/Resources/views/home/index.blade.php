@extends('shop::layouts.master')

@section('slider')
    @include('shop::layouts.slider')
@endsection

@section('content-wrapper')
    @include('shop::grids.featured.featuredproductgrid')
    @include('shop::grids.newproduct.newproductgrid')
    @include('shop::grids.newsupdate.newsupdategrid')
@endsection
