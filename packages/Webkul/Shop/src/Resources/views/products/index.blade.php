@extends('shop::layouts.master')

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



