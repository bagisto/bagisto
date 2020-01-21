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
                        @if ($attribute['type'] == 'file' && $attribute['value'])
                            <td>
                                <a  href="{{ route('shop.product.file.download', [$product->product_id, $attribute['id']])}}">
                                    <i class="icon sort-down-icon download"></i>
                                </a>
                            </td>
                        @elseif ($attribute['type'] == 'image' && $attribute['value'])
                            <td>
                                <a href="{{ route('shop.product.file.download', [$product->product_id, $attribute['id']])}}">
                                    <img src="{{ Storage::url($attribute['value']) }}" style="height: 20px; width: 20px;"/>
                                </a>
                            </td>
                        @else
                            <td>{{ $attribute['value'] }}</td>
                        @endif
                    </tr>
                @endforeach

            </table>
        </div>
    </accordian>
@endif

{!! view_render_event('bagisto.shop.products.view.attributes.after', ['product' => $product]) !!}