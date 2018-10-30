@extends('shop::layouts.master')

@section('page_title')
    {{ __('shop::app.home.page-title') }}
@endsection

@section('content-wrapper')

    {!! DbView::make(core()->getCurrentChannel())->field('home_page_content')->with(['sliderData' => $sliderData])->render() !!}

@endsection
