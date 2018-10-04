@extends('shop::layouts.master')

@section('content-wrapper')

@inject ('productRepository', 'Webkul\Product\Repositories\ProductRepository')

    <div class="main">

        <div class="category-block">
            @include ('shop::products.list.layered-navigation')

            <div class="category-block">

                <div class="hero-image mb-15">
                    <img src="https://images.pexels.com/photos/428338/pexels-photo-428338.jpeg?cs=srgb&dl=adolescent-casual-cute-428338.jpg&fm=jpg" />
                </div>

                <?php $products = $productRepository->findAllByCategory($category->id); ?>

                @include ('shop::products.list.toolbar')

                @inject ('toolbarHelper', 'Webkul\Product\Product\Toolbar')

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
            </div>
        </div>
    </div>

@stop


@push('scripts')

<script>

    window.onload = function() {

        var sort = document.getElementById("sort");
        var filter = document.getElementById("filter");

        sort.addEventListener("click", myFunction);
        filter.addEventListener("click", myFunction);

        function myFunction(){

            let className = document.getElementById(this.id).className;
            var productGrid = document.getElementsByClassName('product-grid max-3-col');
            var filterLayered = document.getElementsByClassName('layered-filter-wrapper');
            var sortLimiter = document.getElementsByClassName('reponsive-sorter-limiter');

            if(className === 'icon filter-icon'){

                for(let i=0 ; i < filterLayered.length ; i++){
                    filterLayered[i].style.display="block";
                    filterLayered[i].style.padding="20px";
                    filterLayered[i].style.width="100%";
                    filterLayered[i].style.marginTop = "-100px";
                }
                for(let i=0 ; i < productGrid.length ; i++){
                    productGrid[i].style.display = "none";
                }
                for(let i=0 ; i < sortLimiter.length ; i++){
                    sortLimiter[i].style.display = "none";
                }

                filter.classList.remove('icon', 'filter-icon');
                filter.classList.add('icon', 'cross-icon');
                sort.classList.remove('icon', 'cross-icon');
                sort.classList.remove('icon', 'sort-icon');
                sort.classList.add('icon', 'sort-icon');

            }else if(className === 'icon sort-icon'){

                for(let i=0 ; i < filterLayered.length ; i++){
                    filterLayered[i].style.display="none";
                }
                for(let i=0 ; i < productGrid.length ; i++){
                    productGrid[i].style.display = "none";
                }
                for(let i=0 ; i < sortLimiter.length ; i++){
                    sortLimiter[i].style.display = "flex";
                    sortLimiter[i].style.justifyContent = "space-between";
                }

                sort.classList.remove('icon', 'sort-icon');
                sort.classList.add('icon', 'cross-icon');
                filter.classList.remove('icon', 'cross-icon');
                filter.classList.remove('icon', 'filter-icon');
                filter.classList.add('icon', 'filter-icon');

            }else {

                for(let i=0 ; i < productGrid.length ; i++){
                    productGrid[i].style.display = "grid";
                }
                for(let i=0 ; i < filterLayered.length ; i++){
                    filterLayered[i].style.display="none";
                }
                for(let i=0 ; i < sortLimiter.length ; i++){
                    sortLimiter[i].style.display = "none";
                }

                sort.classList.remove('icon', 'cross-icon');
                filter.classList.remove('icon', 'cross-icon');
                sort.classList.remove('icon', 'sort-icon');
                filter.classList.remove('icon', 'filter-icon');
                sort.classList.add('icon', 'sort-icon');
                filter.classList.add('icon', 'filter-icon');
            }
        }
    }

</script>

@endpush