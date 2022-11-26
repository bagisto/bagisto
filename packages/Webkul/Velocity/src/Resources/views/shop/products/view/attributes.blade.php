@inject ('productViewHelper', 'Webkul\Product\Helpers\View')

{!! view_render_event('bagisto.shop.products.view.attributes.before', ['product' => $product]) !!}
    @php
        $customAttributeValues = $productViewHelper->getAdditionalData($product);
    @endphp

    @if ($customAttributeValues)
        <accordian :title="'{{ __('shop::app.products.specification') }}'" :active="'{{ $active }}' == true ? true : false">
            <div slot="header">
                <h3 class="no-margin display-inbl">
                    {{ __('velocity::app.products.more-infomation') }}
                </h3>
                <i class="rango-arrow"></i>
            </div>

            <div slot="body">
                <table class="full-specifications">

                    @foreach ($customAttributeValues as $attribute)
                        <tr>
                            @if ($attribute['label'])
                                <td class='fw6'>{{ $attribute['label'] }}</td>
                            @else
                                <td>{{ $attribute['admin_name'] }}</td>
                            @endif

                            @if (
                                $attribute['type'] == 'file'
                                && $attribute['value']
                            )
                                <td>
                                    <a  href="{{ route('shop.product.file.download', [$product->id, $attribute['id']])}}" style="color:black;">
                                        <i class="icon rango-download-1"></i>
                                    </a>
                                </td>
                            @elseif (
                                $attribute['type'] == 'image'
                                && $attribute['value']
                            )
                                <td>
                                    <a href="{{ route('shop.product.file.download', [$product->id, $attribute['id']])}}">
                                        <img src="{{ Storage::url($attribute['value']) }}" style="height: 20px; width: 20px;" alt=""/>
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