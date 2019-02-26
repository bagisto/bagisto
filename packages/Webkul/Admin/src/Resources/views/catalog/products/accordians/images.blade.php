<accordian :title="'{{ __('admin::app.catalog.products.images') }}'" :active="true">
    <div slot="body">

        {!! view_render_event('bagisto.admin.catalog.product.edit_form_accordian.images.controls.before', ['product' => $product]) !!}

        <image-wrapper :button-label="'{{ __('admin::app.catalog.products.add-image-btn-title') }}'" input-name="images" :images='@json($product->images)'></image-wrapper>

        {!! view_render_event('bagisto.admin.catalog.product.edit_form_accordian.images.controls.after', ['product' => $product]) !!}

    </div>
</accordian>