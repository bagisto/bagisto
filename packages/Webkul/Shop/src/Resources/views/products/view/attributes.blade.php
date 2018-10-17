@inject ('productViewHelper', 'Webkul\Product\Helpers\View')

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
                        <td>{{ $attribute['label'] }}</td>
                        <td> - {{ $attribute['value'] }}</td>
                    </tr>

                @endforeach

            </table>
        </div>
    </accordian>
@endif