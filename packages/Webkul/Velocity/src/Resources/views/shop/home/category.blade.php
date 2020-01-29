@inject ('productRepository', 'Webkul\Product\Repositories\ProductRepository')

@php
    $categoryDetails = app('Webkul\Category\Repositories\CategoryRepository')->findByPath($category);
@endphp

@if ($categoryDetails)
    @php
        $products = $productRepository->getAll($categoryDetails->id);
    @endphp

    @if ($products->count())
        <div class="container-fluid remove-padding-margin">
            <card-list-header
                view-all="{{ route('shop.productOrCategory.index', $categoryDetails->slug) }}"
                heading="{{ $categoryDetails->name }}">
            </card-list-header>

            <div class="carousel-products vc-full-screen">
                <carousel-component
                    slides-per-page="6"
                    navigation-enabled="hide"
                    pagination-enabled="hide"
                    :slides-count="{{ sizeof($products) }}"
                    id="{{ $categoryDetails->name }}-carousel">

                    @foreach ($products as $index => $product)
                        <slide slot="slide-{{ $index }}">
                            @include ('shop::products.list.card', ['product' => $product])
                        </slide>
                    @endforeach
                </carousel-component>
            </div>

            <div class="carousel-products vc-small-screen">
                <carousel-component
                    slides-per-page="2"
                    navigation-enabled="hide"
                    pagination-enabled="hide"
                    :slides-count="{{ sizeof($products) }}"
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

@push('scripts')
    {{-- <script type="text/x-template" id="category-products-template">
        <div class="container-fluid remove-padding-margin">
            <card-list-header
                view-all="{{ route('shop.productOrCategory.index', $categoryDetails->slug) }}"
                heading="{{ $categoryDetails->name }}">
            </card-list-header>

            <div class="carousel-products vc-full-screen">
                <carousel-component
                    slides-per-page="6"
                    navigation-enabled="hide"
                    pagination-enabled="hide"
                    :slides-count="{{ sizeof($products) }}"
                    id="{{ $categoryDetails->name }}-carousel">

                    @foreach ($products as $index => $product)
                        <slide slot="slide-{{ $index }}">
                            @include ('shop::products.list.card', ['product' => $product])
                        </slide>
                    @endforeach
                </carousel-component>
            </div>

            <div class="carousel-products vc-small-screen">
                <carousel-component
                    slides-per-page="2"
                    navigation-enabled="hide"
                    pagination-enabled="hide"
                    :slides-count="{{ sizeof($products) }}"
                    id="{{ $categoryDetails->name }}-carousel">

                    @foreach ($products as $index => $product)
                        <slide slot="slide-{{ $index }}">
                            @include ('shop::products.list.card', ['product' => $product])
                        </slide>
                    @endforeach
                </carousel-component>
            </div>
        </div>
    </script>

    <script type="text/javascript">
        (() => {
            debugger
            "{{ $categoryDetails->name }}"
            Vue.component('category-products', {
                template: '#category-products-template',
            })
        })()
    </script> --}}
@endpush