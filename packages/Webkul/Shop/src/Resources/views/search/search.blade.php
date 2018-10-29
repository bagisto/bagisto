@extends('shop::layouts.master')

@section('page_title')
    {{ __('shop::app.search.page-title') }}
@endsection

@section('content-wrapper')
    @if(!$results)
        {{  __('shop::app.search.no-results') }}
    @endif
@endsection
