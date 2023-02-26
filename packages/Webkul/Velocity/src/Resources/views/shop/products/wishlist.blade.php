@inject ('wishListHelper', 'Webkul\Customer\Helpers\Wishlist')

{!! view_render_event('bagisto.shop.products.wishlist.before') !!}

    @auth('customer')
        @php
            /* search wishlist on the basis of product's id so that wishlist id can be catched */
            $wishlist = $wishListHelper->getWishlistProduct($product);

            /* link making */
            $href = isset($route) ? $route : ($wishlist ? route('shop.customer.wishlist.remove', $wishlist->id) : route('shop.customer.wishlist.add', $product->id));

            /* method */
            $method = isset($route) ? 'POST' : ( $wishlist ? 'DELETE' : 'POST' );

            /* is confirmation needed */
            $isConfirm = isset($route) ? 'true' : 'false';

            /* title */
            $title = $wishlist ? __('velocity::app.shop.wishlist.remove-wishlist-text') : __('velocity::app.shop.wishlist.add-wishlist-text');     
        @endphp
        
        <a
            class="unset wishlist-icon wishlist{{ $addWishlistClass ?? '' }} text-right"
            href="javascript:void(0);"
            title="{{ $title }}"
            onclick="submitWishlistForm(
                '{{ $href }}',
                '{{ $method }}',
                {{ $isConfirm }},
                '{{ csrf_token() }}'
            )"
            >

            @if (request()->routeIs('shop.customer.wishlist.index'))
                <wishlist-component add-class="rango-delete fs24"></wishlist-component>
            @else
                <wishlist-component active="{{ $wishlist ? false : true }}"></wishlist-component>
            @endif
            
            
            @if (isset($text))
                {!! $text !!}
            @elseif ($showText ?? false)
                <span>{{__('admin::app.admin.system.wishlist')}}</span>
            @endif
        </a>        
    @endauth

    @guest('customer')
        <form           
            id="wishlist-{{ $product->id }}"
            action="{{ route('shop.customer.wishlist.add', $product->id) }}"
            method="POST">
            @csrf
            <div>
                <a
                    class="unset wishlist-icon wishlist {{ $addWishlistClass ?? '' }} text-right"
                    href="javascript:void(0);"
                    title="{{ __('velocity::app.shop.wishlist.add-wishlist-text') }}"
                    onclick="document.getElementById('wishlist-{{ $product->id }}').submit();"
                >
                    <wishlist-component active="false"></wishlist-component>

                    @if (isset($text))
                        {!! $text !!}
                    @elseif ($showText ?? false)
                        <span>{{__('admin::app.admin.system.wishlist')}}</span>
                    @endif
                </a>
            </div>
        </form>
    @endauth

{!! view_render_event('bagisto.shop.products.wishlist.after') !!}
