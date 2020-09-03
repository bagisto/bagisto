@inject ('toolbarHelper', 'Webkul\Product\Helpers\Toolbar')
@inject ('productImageHelper', 'Webkul\Product\Helpers\ProductImage')

@extends('shop::customers.account.index')

@section('page_title')
    {{ __('shop::app.customer.account.wishlist.page-title') }}
@endsection

@section('page-detail-wrapper')
    <div class="account-head">
        <span class="account-heading">{{ __('shop::app.customer.account.wishlist.title') }}</span>

        @if (count($items))
            <div class="account-action pull-right">
                <a
                    class="remove-decoration theme-btn light"
                    href="{{ route('customer.wishlist.removeall') }}">
                    {{ __('shop::app.customer.account.wishlist.deleteall') }}
                </a>
            </div>
        @endif
    </div>

    {!! view_render_event('bagisto.shop.customers.account.wishlist.list.before', ['wishlist' => $items]) !!}

    <div class="account-items-list row wishlist-container">

        @if ($items->count())
            @foreach ($items as $item)
                <div class="col-12 lg-card-container list-card product-card row">
                    <div class="product-image">
                        <a
                            title="{{ $item->product->name }}"
                            href="{{ route('shop.productOrCategory.index', $item->product->url_key) }}">

                            <img
                                src="{{ $productImageHelper->getProductBaseImage($item->product)['medium_image_url'] }}"
                                :onerror="`this.src='${this.$root.baseUrl}/vendor/webkul/ui/assets/images/product/large-product-placeholder.png'`" />
                            <div class="quick-view-in-list">
                                <product-quick-view-btn :quick-view-details="{{ json_encode($item->product) }}"></product-quick-view-btn>
                            </div>
                        </a>
                    </div>

                    <div class="product-information">
                        <div>
                            <div class="product-name">
                                <a
                                    href="{{ route('shop.productOrCategory.index', $item->product->url_key) }}"
                                    title="{{ $item->product->name }}" class="unset">

                                    <span class="fs16">{{ $item->product->name }}</span>

                                    @if (isset($item->additional['attributes']))
                                        <div class="item-options">

                                            @foreach ($item->additional['attributes'] as $attribute)
                                                <b>{{ $attribute['attribute_name'] }} : </b>{{ $attribute['option_label'] }}</br>
                                            @endforeach

                                        </div>
                                    @endif
                                </a>
                            </div>

                            <div class="product-price">
                                @include ('shop::products.price', ['product' => $item->product])
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach

            <div class="bottom-toolbar">
                {{ $items->links() }}
            </div>
        @else
            <div class="empty">
                {{ __('customer::app.wishlist.empty') }}
            </div>
        @endif
    </div>

    {!! view_render_event('bagisto.shop.customers.account.wishlist.list.after', ['wishlist' => $items]) !!}
@endsection
