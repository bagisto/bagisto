@extends('shop::layouts.master')

@section('content-wrapper')
    <div class="account-content">
        @inject ('productImageHelper', 'Webkul\Product\Helpers\ProductImage')

        @include('shop::customers.account.partials.sidemenu')

        @inject ('reviewHelper', 'Webkul\Product\Helpers\Review')

        <div class="account-layout">

            <div class="account-head mb-15">
                <span class="account-heading">{{ __('shop::app.wishlist.title') }}</span>

                @if (count($items))
                    <div class="account-action">
                        <a href="{{ route('customer.wishlist.removeall') }}">{{ __('shop::app.wishlist.deleteall') }}</a>
                    </div>
                @endif
                
                <div class="horizontal-rule"></div>
            </div>

            {!! view_render_event('bagisto.shop.customers.account.wishlist.list.before', ['wishlist' => $items]) !!}

            <div class="account-items-list">

                @if ($items->count())
                    @foreach ($items as $item)
                        <div class="account-item-card mt-15 mb-15">
                            <div class="media-info">
                                @php
                                    $image = $item->product->getTypeInstance()->getBaseImage($item);
                                @endphp

                                <img class="media" src="{{ $image['small_image_url'] }}" />

                                <div class="info">
                                    <div class="product-name">
                                        {{ $item->product->name }}
                                    
                                        @if (isset($item->additional['attributes']))
                                            <div class="item-options">
                                                
                                                @foreach ($item->additional['attributes'] as $attribute)
                                                    <b>{{ $attribute['attribute_name'] }} : </b>{{ $attribute['option_label'] }}</br>
                                                @endforeach

                                            </div>
                                        @endif
                                    </div>

                                    <span class="stars" style="display: inline">
                                        @for ($i = 1; $i <= $reviewHelper->getAverageRating($item->product); $i++)
                                            <span class="icon star-icon"></span>
                                        @endfor
                                    </span>
                                </div>
                            </div>

                            <div class="operations">
                                <a class="mb-50" href="{{ route('customer.wishlist.remove', $item->id) }}">
                                    <span class="icon trash-icon"></span>
                                </a>

                                <a href="{{ route('customer.wishlist.move', $item->id) }}" class="btn btn-primary btn-md">
                                    {{ __('shop::app.wishlist.move-to-cart') }}
                                </a>
                            </div>
                        </div>

                        <div class="horizontal-rule mb-10 mt-10"></div>
                    @endforeach
                @else
                    <div class="empty">
                        {{ __('customer::app.wishlist.empty') }}
                    </div>
                @endif
            </div>

            {!! view_render_event('bagisto.shop.customers.account.wishlist.list.after', ['wishlist' => $items]) !!}

        </div>
    </div>
@endsection