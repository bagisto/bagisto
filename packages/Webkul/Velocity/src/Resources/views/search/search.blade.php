@extends('shop::layouts.master')

@section('page_title')
    {{ __('shop::app.search.page-title') }}
@endsection

@section('content-wrapper')
    <section class="cart-details row offset-1">
        @if (! $results)
            <h1 class="fw6 row col-12">{{  __('shop::app.search.no-results') }}</h1>
        @else
            @if ($results->isEmpty())
                <h1 class="fw6 row col-12">{{ __('shop::app.products.whoops') }}</h1>
                <span>{{ __('shop::app.search.no-results') }}</span>
            @else
                @if ($results->total() == 1)
                    <h2 class="fw6 row col-12 mb20">
                        {{ $results->total() }} {{ __('shop::app.search.found-result') }}
                    </h2>
                @else
                    <h2 class="fw6 row col-12 mb20">
                        {{ $results->total() }} {{ __('shop::app.search.found-results') }}
                    </h2>
                @endif

                @foreach ($results as $productFlat)

                    @include('shop::products.list.card', ['product' => $productFlat->product])

                @endforeach

                @include('ui::datagrid.pagination')
            @endif
        @endif
    </section>
@endsection