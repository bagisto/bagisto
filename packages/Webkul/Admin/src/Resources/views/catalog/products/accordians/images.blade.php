<accordian :title="'{{ __($accordian['name']) }}'" :active="true">
    <div slot="body">

    
        <image-wrapper :button-label="'{{ __('admin::app.catalog.products.add-image-btn-title') }}'" input-name="images" :images='@json($product->images)'></image-wrapper>

    </div>
</accordian>