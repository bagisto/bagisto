@extends('shop::layouts.master')

@section('content-wrapper')

    @include ('shop::products.list.layered-navigation')

    <div class="main" style="display: inline-block">

        @inject ('productRepository', 'Webkul\Product\Repositories\ProductRepository')

        <?php $products = $productRepository->findAllByCategory($category->id); ?>

        @include ('shop::products.list.toolbar')

        @inject ('toolbarHelper', 'Webkul\Product\Product\Toolbar')

        @if ($toolbarHelper->getCurrentMode() == 'grid')
            <div class="product-grid max-3-col">

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

@stop