@inject ('productRepository', 'Webkul\Product\Repositories\ProductRepository')

@php
    $categoryDetails = app('Webkul\Category\Repositories\CategoryRepository')->findByPath($category);
@endphp

@if ($categoryDetails)
    @php
        $products = $productRepository->getAll($categoryDetails->id);
    @endphp

    @if ($products->count())
        <div class="container-fluid">
            <card-list-header
                view-all="{{ route('shop.productOrCategory.index', ['slug' => $categoryDetails->slug]) }}"
                heading="{{ $categoryDetails->name }}">
            </card-list-header>

            <div class="row flex-nowrap">
                <carousel-component
                    :slides-count="{{ sizeof($products) }}"
                    slides-per-page="6"
                    id="{{ $categoryDetails->name }}-carousel">

                    @foreach ($products as $index => $product)

                        <slide slot="slide-{{ $index }}">
                            @include ('shop::products.list.card', ['product' => $product])
                        </slide>

                    @endforeach

                </carousel-component>
            </div>
        </div>
    @endif
@endif