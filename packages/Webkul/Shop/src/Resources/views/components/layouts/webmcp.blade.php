{!! view_render_event('bagisto.shop.layout.webmcp.before') !!}

<form
    action="{{ route('shop.webmcp.category') }}"
    method="GET"
    toolname="view_category"
    tooldescription="{{ trans('shop::app.components.layouts.webmcp.view-category') }}"
    toolautosubmit
>
    <input
        type="text"
        name="query"
        toolparamdescription="{{ trans('shop::app.components.layouts.webmcp.view-category-query') }}"
    >
</form>

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
        toolparamdescription="{{ trans('shop::app.components.layouts.webmcp.view-product-query') }}"
    >
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
        toolparamdescription="{{ trans('shop::app.components.layouts.webmcp.add-to-wishlist-query') }}"
    >
</form>

<form
    action="{{ route('shop.customers.account.wishlist.index') }}"
    method="GET"
    toolname="view_wishlist"
    tooldescription="{{ trans('shop::app.components.layouts.webmcp.view-wishlist') }}"
    toolautosubmit
>
</form>

<form
    action="{{ route('shop.checkout.cart.index') }}"
    method="GET"
    toolname="view_cart"
    tooldescription="{{ trans('shop::app.components.layouts.webmcp.view-cart') }}"
    toolautosubmit
>
</form>

<form
    action="{{ route('shop.checkout.onepage.index') }}"
    method="GET"
    toolname="proceed_to_checkout"
    tooldescription="{{ trans('shop::app.components.layouts.webmcp.proceed-to-checkout') }}"
    toolautosubmit
>
</form>

{!! view_render_event('bagisto.shop.layout.webmcp.after') !!}
