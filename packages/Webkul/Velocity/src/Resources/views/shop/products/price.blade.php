{!! view_render_event('bagisto.shop.products.price.before', ['product' => $product]) !!}

@php 
    if($product->getTypeInstance()->haveSpecialPrice()){
        $specialPrice = $product->getTypeInstance()->getSpecialPrice();
        $proce = $product->price;
        $Discount_Precent = "(".round(($specialPrice*100)/$proce)."% off)";  

    }else{
       $Discount_Precent ='';
    } 
@endphp 

<div class="product-price">
    {!! $product->getTypeInstance()->getPriceHtml() !!} {{ $Discount_Precent }}
</div>

{!! view_render_event('bagisto.shop.products.price.after', ['product' => $product]) !!}