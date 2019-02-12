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
            $('.responsive-layred-filter').css('display','none');
            $(".sort-icon, .filter-icon").on('click', function(e){
                var currentElement = $(e.currentTarget);
                if (currentElement.hasClass('sort-icon')) {
                    currentElement.removeClass('sort-icon');
                    currentElement.addClass('icon-menu-close-adj');
                    currentElement.next().removeClass();
                    currentElement.next().addClass('icon filter-icon');
                    $('.responsive-layred-filter').css('display','none');
                    $('.pager').css('display','flex');
                    $('.pager').css('justify-content','space-between');
                } else if (currentElement.hasClass('filter-icon')) {
                    currentElement.removeClass('filter-icon');
                    currentElement.addClass('icon-menu-close-adj');
                    currentElement.prev().removeClass();
                    currentElement.prev().addClass('icon sort-icon');
                    $('.pager').css('display','none');
                    $('.responsive-layred-filter').css('display','block');
                    $('.responsive-layred-filter').css('margin-top','10px');
                } else {
                    currentElement.removeClass('icon-menu-close-adj');
                    $('.responsive-layred-filter').css('display','none');
                    $('.pager').css('display','none');
                    if ($(this).index() == 0) {
                        currentElement.addClass('sort-icon');
                    } else {
                        currentElement.addClass('filter-icon');
                    }
                }
            });
        });
    </script>
@endpush