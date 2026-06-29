{!! view_render_event('bagisto.shop.layout.webmcp.before') !!}

<form
    action="{{ route('shop.webmcp.product') }}"
    method="GET"
    toolname="view_product"
    tooldescription="{{ trans('shop::app.components.layouts.webmcp.view-product') }}"
    toolautosubmit
>
    <input
        type="text"
        name="query"
        aria-label="{{ trans('shop::app.components.layouts.webmcp.view-product-query') }}"
        toolparamdescription="{{ trans('shop::app.components.layouts.webmcp.view-product-query') }}"
    >

    <button type="submit" class="hidden" aria-hidden="true"></button>
</form>

<form
    action="{{ route('shop.webmcp.wishlist.add') }}"
    method="GET"
    toolname="add_to_wishlist"
    tooldescription="{{ trans('shop::app.components.layouts.webmcp.add-to-wishlist') }}"
    toolautosubmit
>
    <input
        type="text"
        name="query"
        aria-label="{{ trans('shop::app.components.layouts.webmcp.add-to-wishlist-query') }}"
        toolparamdescription="{{ trans('shop::app.components.layouts.webmcp.add-to-wishlist-query') }}"
    >

    <button type="submit" class="hidden" aria-hidden="true"></button>
</form>

<form
    action="{{ route('shop.customers.account.wishlist.index') }}"
    method="GET"
    toolname="view_wishlist"
    tooldescription="{{ trans('shop::app.components.layouts.webmcp.view-wishlist') }}"
    toolautosubmit
>
    <button type="submit" class="hidden" aria-hidden="true"></button>
</form>

<form
    action="{{ route('shop.checkout.cart.index') }}"
    method="GET"
    toolname="view_cart"
    tooldescription="{{ trans('shop::app.components.layouts.webmcp.view-cart') }}"
    toolautosubmit
>
    <button type="submit" class="hidden" aria-hidden="true"></button>
</form>

<form
    action="{{ route('shop.checkout.onepage.index') }}"
    method="GET"
    toolname="proceed_to_checkout"
    tooldescription="{{ trans('shop::app.components.layouts.webmcp.proceed-to-checkout') }}"
    toolautosubmit
>
    <button type="submit" class="hidden" aria-hidden="true"></button>
</form>

{!! view_render_event('bagisto.shop.layout.webmcp.after') !!}
