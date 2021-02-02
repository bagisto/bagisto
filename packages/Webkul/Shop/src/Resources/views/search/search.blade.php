@extends('shop::layouts.master')

@section('page_title')
    {{ __('shop::app.search.page-title') }}
@endsection

@section('content-wrapper')
    @if (request('image-search'))
        <image-search-result-component></image-search-result-component>
    @endif

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
                <div class="search-result-status mb-20">
                    <span>
                        <b>{{ $results->total() }} </b>

                        {{ ($results->total() == 1) ? __('shop::app.search.found-result') : __('shop::app.search.found-results') }}
                    </span>
                </div>

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

@push('scripts')

    <script type="text/x-template" id="image-search-result-component-template">
        <div class="image-search-result">
            <div class="searched-image">
                <img :src="searched_image_url" alt=""/>
            </div>

            <div class="searched-terms">
                <h3>{{ __('shop::app.search.analysed-keywords') }}</h3>

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
                    searched_image_url: localStorage.searched_image_url,

                    searched_terms: []
                }
            },

            created: function() {
                if (localStorage.searched_terms && localStorage.searched_terms != '') {
                    this.searched_terms = localStorage.searched_terms.split('_');
                }
            }
        });
    </script>

@endpush