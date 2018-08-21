<div class="product-ratings">

    @inject ('reviewHelper', 'Webkul\Shop\Product\Review')

    @for ($i = 1; $i <= $reviewHelper->getAverageRating($product); $i++)

        <span class="icon star-icon"></span>
    
    @endfor
    
</div>