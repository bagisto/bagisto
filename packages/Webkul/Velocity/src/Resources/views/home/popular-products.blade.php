<div class="container-fluid popular-products no-padding">

    <card-list-header :show-tabs="true"></card-list-header>

    {!! view_render_event('bagisto.shop.popular.products.before') !!}

    <card-list-content :card-details="{{ json_encode(array_slice($sampleProducts, 1)) }}"></card-list-content>

    {!! view_render_event('bagisto.shop.popular.products.after') !!}


</div>
