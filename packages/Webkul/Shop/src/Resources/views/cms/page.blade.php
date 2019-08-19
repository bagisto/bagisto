@extends('shop::layouts.master')

@section('page_title')
    {{ $page->page_title }}
@endsection

@section('head')
    @isset($page->meta_title)
        <meta name="title" content="{{ $page->meta_title }}" />
    @endisset

    @isset($page->meta_description)
        <meta name="description" content="{{ $page->meta_description }}" />
    @endisset

    @isset($page->meta_keywords)
        <meta name="keywords" content="{{ $page->meta_keywords }}" />
    @endisset

    <link href="{{ asset('themes/default/assets/css/shop.css') }}" rel="stylesheet" />
@endsection

@section('content-wrapper')
    {!! DbView::make($page)->field('html_content')->render() !!}
@endsection

@push('scripts')
    <script src="{{ asset('themes/default/assets/js/shop.js') }}" type="text/javascript"></script>
@endpush