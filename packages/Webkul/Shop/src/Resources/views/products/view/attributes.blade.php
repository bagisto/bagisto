@inject ('productViewHelper', 'Webkul\Product\Product\View')

<accordian :title="{{ __('shop::app.products.specification') }}" :active="false">
    <div slot="header">
        {{ __('shop::app.products.specification') }}
        <i class="icon expand-icon right"></i>
    </div>

    <div slot="body">
        <table class="full-specifications">

            @foreach ($productViewHelper->getAdditionalData($product) as $attribute)

                <tr>
                    <td>{{ $attribute['label'] }}</td>
                    <td> - {{ $attribute['value'] }}</td>
                </tr>

            @endforeach
            
        </table>
    </div>
</accordian>