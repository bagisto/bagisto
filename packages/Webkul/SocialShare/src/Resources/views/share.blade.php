@if (core()->getConfigData('catalog.products.social_share.enabled'))
    @php
        $message = core()->getConfigData('catalog.products.social_share.share_message');
    @endphp

    <div class="flex gap-[25px]">
        {!! view_render_event('bagisto.shop.products.view.share.before', ['product' => $product]) !!}

        <div class="hidden gap-[10px] justify-center items-center max-md:flex cursor-pointer">
            <span class="icon-share text-[24px]"></span>
            <a href="intent://share/#Intent;action=android.intent.action.SEND;type=text/plain;S.android.intent.extra.TEXT={{ rawurlencode($product->name . ' ' . route('shop.product_or_category.index', [$product->url_key])) }};end">
                @lang('admin::app.configuration.index.catalog.products.social-share.share')
            </a>
        </div>

        <div class="max-md:hidden">
            <ul class="flex gap-[15px]">
                @foreach(['facebook', 'twitter', 'instagram', 'pinterest', 'linkedin', 'whatsapp', 'email'] as $social)
                    @if (! core()->getConfigData('catalog.products.social_share.' . $social))
                        @continue
                    @endif

                    @include('social_share::links.' . $social , compact('product', 'message'))
                @endforeach
            </ul>
        </div>

        {!! view_render_event('bagisto.shop.products.view.share.after', ['product' => $product]) !!}
    </div>
@endif
