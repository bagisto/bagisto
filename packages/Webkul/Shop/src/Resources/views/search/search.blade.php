@extends('shop::layouts.master')

@section('page_title')
    {{ __('shop::app.search.page-title') }}
@endsection

@section('content-wrapper')
    @if (! $results)
        {{  __('shop::app.search.no-results') }}
    @endif

    @if ($results)
        <div class="main mb-30" style="min-height: 27vh;">
            @if ($results->isEmpty())
                <div class="search-result-status">
                    <h2>{{ __('shop::app.products.whoops') }}</h2>
                    <span>{{ __('shop::app.search.no-results') }}</span>
                </div>
            @else
                @if ($results->count() == 1)
                    <div class="search-result-status mb-20">
                        <span><b>{{ $results->count() }} </b>{{ __('shop::app.search.found-result') }}</span>
                    </div>
                @else
                    <div class="search-result-status mb-20">
                        <span><b>{{ $results->count() }} </b>{{ __('shop::app.search.found-results') }}</span>
                    </div>
                @endif

                <div class="product-grid-4">
                    @foreach ($results as $productFlat)

                        @include('shop::products.list.card', ['product' => $productFlat->product])

                    @endforeach
                </div>

                @include('ui::datagrid.pagination')
            @endif
        </div>
    @endif
@endsection