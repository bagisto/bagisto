@extends('shop::layouts.master')

@section('content-wrapper')
    
    @include ('shop::products.layered-navigation')

    <div class="main" style="display: inline-block">

        <div class="product-grid max-3-col">

            @inject ('productHelper', 'Webkul\Product\Product\Collection')
            
            <?php $products = $productHelper->getCollection($category->id); ?>
            
            @foreach ($products as $product)

                @include ('shop::products.card', ['product' => $product])

            @endforeach

        </div>

        <div class="bottom-toolbar">

            {{ $products->appends(request()->input())->links() }}

        </div>
        
    </div>

@stop

@push('scripts')

@endpush