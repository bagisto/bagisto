@extends('shop::layouts.master')

@section('page_title')
    {{ __('shop::app.search.page-title') }}
@endsection

@section('content-wrapper')
    @if(!$products)
        {{  __('shop::app.search.no-results') }}
    @endif

    <div class="main mb-30" style="min-height: 27vh;">
    
        @if($products->isEmpty())
            <div class="search-result-status">
                <h2>{{ __('shop::app.products.whoops') }}</h2>
                <span>{{ __('shop::app.search.no-results') }}</span>
            </div>
        @else
            @if($products->count() == 1)
                <div class="search-result-status mb-20">
                    <span><b>{{ $products->count() }} </b>{{ __('shop::app.search.found-result') }}</span>
                </div>
            @else
                <div class="search-result-status mb-20">
                    <span><b>{{ $products->count() }} </b>{{ __('shop::app.search.found-results') }}</span>
                </div>
            @endif
            {{-- @include ('shop::products.list.toolbar')

            @inject ('toolbarHelper', 'Webkul\Product\Helpers\Toolbar') --}}
            <div class="product-grid-4">
                @foreach ($products as $product)
                    @include ('shop::products.list.card', ['product' => $product])
                @endforeach
            </div>
        @endif
    </div>
    
@endsection
