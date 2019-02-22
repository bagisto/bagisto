@inject ('productViewHelper', 'Webkul\Product\Helpers\View')

{!! view_render_event('bagisto.shop.products.view.attributes.before', ['product' => $product]) !!}

@if ($customAttributeValues = $productViewHelper->getAdditionalData($product))
    <accordian :title="'{{ __('shop::app.products.specification') }}'" :active="false">
        <div slot="header">
            {{ __('shop::app.products.specification') }}
            <i class="icon expand-icon right"></i>
        </div>

        <div slot="body">
            <table class="full-specifications">

                @foreach ($customAttributeValues as $attribute)

                    <tr>
                        @if ($attribute['label'])
                            <td>{{ $attribute['label'] }}</td>
                        @else
                            <td>{{ $attribute['admin_name'] }}</td>
                        @endif
                            <td>{{ $attribute['value'] }}</>
                    </tr>

                @endforeach

            </table>
        </div>
    </accordian>
@endif

{!! view_render_event('bagisto.shop.products.view.attributes.after', ['product' => $product]) !!}