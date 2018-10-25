@extends('shop::layouts.master')

@section('page_title')
    {{ $category->meta_title ?? $category->name }}
@stop

@section('seo')
    <meta name="description" content="{{ $category->meta_description }}"/>
    <meta name="description" content="{{ $category->meta_keywords }}"/>
@stop

@section('content-wrapper')
    @inject ('productRepository', 'Webkul\Product\Repositories\ProductRepository')

    <div class="main">
        <div class="category-container">

            @include ('shop::products.list.layered-navigation')

            <div class="category-block">
                <div class="hero-image mb-35">
                    <img src="https://images.pexels.com/photos/428338/pexels-photo-428338.jpeg?cs=srgb&dl=adolescent-casual-cute-428338.jpg&fm=jpg" />
                </div>

                <?php $products = $productRepository->findAllByCategory($category->id); ?>

                @if ($products->count())

                    @include ('shop::products.list.toolbar')

                    @inject ('toolbarHelper', 'Webkul\Product\Helpers\Toolbar')

                    @if ($toolbarHelper->getCurrentMode() == 'grid')
                        <div class="product-grid-3">
                            @foreach ($products as $product)
                                @include ('shop::products.list.card', ['product' => $product])
                            @endforeach
                        </div>
                    @else
                        <div class="product-list">
                            @foreach ($products as $product)
                                @include ('shop::products.list.card', ['product' => $product])
                            @endforeach
                        </div>
                    @endif

                    <div class="bottom-toolbar">
                        {{ $products->appends(request()->input())->links() }}
                    </div>

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

            if(sort && filter) {
                sort.addEventListener("click", sortFilter);
                filter.addEventListener("click", sortFilter);
            }

            function sortFilter() {
                var className = document.getElementById(this.id).className;

                if(className === 'icon sort-icon') {
                    sort.classList.remove("sort-icon");
                    sort.classList.add("icon-menu-close-adj");

                    filter.classList.remove("icon-menu-close-adj");
                    filter.classList.add("filter-icon");

                    sortLimit.style.display = "flex";
                    sortLimit.style.justifyContent = "space-between";
                    layerFilter.style.display ="none";
                } else if(className === 'icon filter-icon') {
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