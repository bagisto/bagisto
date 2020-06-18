@inject ('toolbarHelper', 'Webkul\Product\Helpers\Toolbar')

@extends('shop::layouts.master')

@section('page_title')
    {{ __('shop::app.search.page-title') }}
@endsection

@section('content-wrapper')
    <div class="container">
        <section class="search-container row">
            @if (request('image-search'))
                <image-search-result-component></image-search-result-component>
            @endif

            @if ($results && $results->count())
                <div class="filters-container col-12">
                    @include ('shop::products.list.toolbar')
                </div>
            @endif

            @if (! $results)
                <h1 class="fw6 col-12">{{  __('shop::app.search.no-results') }}</h1>
            @else
                @if ($results->isEmpty())
                    <h1 class="fw6 col-12">{{ __('shop::app.products.whoops') }}</h1>
                    <span class="col-12">{{ __('shop::app.search.no-results') }}</span>
                @else
                    @if ($results->total() == 1)
                        <h2 class="fw6 col-12 mb20">
                            {{ $results->total() }} {{ __('shop::app.search.found-result') }}
                        </h2>
                    @else
                        <h2 class="fw6 col-12 mb20">
                            {{ $results->total() }} {{ __('shop::app.search.found-results') }}
                        </h2>
                    @endif

                    @foreach ($results as $productFlat)
                        @if ($toolbarHelper->getCurrentMode() == 'grid')
                            @include('shop::products.list.card', ['product' => $productFlat->product])
                        @else
                            @include('shop::products.list.card', [
                                'list' => true,
                                'product' => $productFlat->product
                            ])
                        @endif
                    @endforeach

                    @include('ui::datagrid.pagination')
                @endif
            @endif
        </section>
    </div>
@endsection

@push('scripts')
    <script type="text/x-template" id="image-search-result-component-template">
        <div class="image-search-result">
            <div class="searched-image">
                <img :src="searchedImageUrl"/>
            </div>

            <div class="searched-terms">
                <h3 class="fw6 fs20 mb-4">
                    {{ __('shop::app.search.analysed-keywords') }}
                </h3>

                <div class="term-list">
                    <a v-for="term in searched_terms" :href="'{{ route('shop.search.index') }}?term=' + term">
                        @{{ term }}
                    </a>
                </div>
            </div>
        </div>
    </script>

    <script>
        Vue.component('image-search-result-component', {
            template: '#image-search-result-component-template',

            data: function() {
                return {
                    searched_terms: [],
                    searchedImageUrl: localStorage.searchedImageUrl,
                }
            },

            created: function() {
                this.searched_terms = localStorage.searched_terms.split('_');
            }
        });
    </script>
@endpush