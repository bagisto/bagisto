@extends('shop::layouts.master')

@section('page_title')
    {{ __('shop::app.home.page-title') }}
@endsection

@section('content-wrapper')

    {!! view_render_event('bagisto.shop.home.content.before') !!}

    {!! DbView::make(core()->getCurrentChannel())->field('home_page_content')->with(['sliderData' => $sliderData])->render() !!}
    
    {{ view_render_event('bagisto.shop.home.content.after') }}

@endsection
