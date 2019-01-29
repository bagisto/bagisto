@extends('shop::layouts.master')

@section('page_title')
    {{ $category->meta_title ?? $category->name }}
@stop

@section('seo')
    <meta name="description" content="{{ $category->meta_description }}"/>
    <meta name="keywords" content="{{ $category->meta_keywords }}"/>
@stop

@section('content-wrapper')
    @inject ('productRepository', 'Webkul\Product\Repositories\ProductRepository')

    <div class="main">
        {!! view_render_event('bagisto.shop.products.index.before', ['category' => $category]) !!}

        <div class="category-container">

            @include ('shop::products.list.layered-navigation')

            <div class="category-block">
                <div class="hero-image mb-35">
                    @if (!is_null($category->image))
                        <img class="logo" src="{{ $category->image_url }}" />
                    @endif
                </div>

                <?php $products = $productRepository->findAllByCategory($category->id); ?>

                @if ($products->count())

                    @include ('shop::products.list.toolbar')

                    @inject ('toolbarHelper', 'Webkul\Product\Helpers\Toolbar')

                    @if ($toolbarHelper->getCurrentMode() == 'grid')
                        <div class="product-grid-3">
                            @foreach ($products as $productFlat)

                                @include ('shop::products.list.card', ['product' => $productFlat->product])

                            @endforeach
                        </div>
                    @else
                        <div class="product-list">
                            @foreach ($products as $product)

                                @include ('shop::products.list.card', ['product' => $productFlat->product])

                            @endforeach
                        </div>
                    @endif

                    {!! view_render_event('bagisto.shop.products.index.pagination.before') !!}

                    <div class="bottom-toolbar">
                        {{ $products->appends(request()->input())->links() }}
                    </div>

                    {!! view_render_event('bagisto.shop.products.index.pagination.after') !!}

                @else

                    <div class="product-list empty">
                        <h2>{{ __('shop::app.products.whoops') }}</h2>

                        <p>
                            {{ __('shop::app.products.empty') }}
                        </p>
                    </div>

                @endif
            </div>
        </div>

        {!! view_render_event('bagisto.shop.products.index.after', ['category' => $category]) !!}
    </div>
@stop

@push('scripts')
    <script>
        $(document).ready(function() {
            var sort = document.getElementById("sort");
            var filter = document.getElementById("filter");
            var sortLimit = document.getElementsByClassName('pager')[0];
            var layerFilter = document.getElementsByClassName('responsive-layred-filter')[0];

            layerFilter.style.display ="none";

            if (sort && filter) {
                sort.addEventListener("click", sortFilter);
                filter.addEventListener("click", sortFilter);
            }

            function sortFilter() {
                var className = document.getElementById(this.id).className;

                if (className === 'icon sort-icon') {
                    sort.classList.remove("sort-icon");
                    sort.classList.add("icon-menu-close-adj");

                    filter.classList.remove("icon-menu-close-adj");
                    filter.classList.add("filter-icon");

                    sortLimit.style.display = "flex";
                    sortLimit.style.justifyContent = "space-between";
                    layerFilter.style.display ="none";
                } else if (className === 'icon filter-icon') {
                    filter.classList.remove("filter-icon");
                    filter.classList.add("icon-menu-close-adj");

                    sort.classList.remove("icon-menu-close-adj");
                    sort.classList.add("sort-icon");

                    layerFilter.style.display = "block";
                    layerFilter.style.marginTop = "10px";

                    sortLimit.style.display = "none";
                } else {
                    sort.classList.remove("icon-menu-close-adj");
                    sort.classList.add("sort-icon");

                    filter.classList.remove("icon-menu-close-adj");
                    filter.classList.add("filter-icon");

                    sortLimit.style.display = "none";
                    layerFilter.style.display = "none";
                }
            }
        });
    </script>
@endpush