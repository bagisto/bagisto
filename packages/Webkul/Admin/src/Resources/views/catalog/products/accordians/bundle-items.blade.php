{!! view_render_event('bagisto.admin.catalog.product.edit_form_accordian.bundle.before', ['product' => $product]) !!}

<accordian :title="'{{ __('admin::app.catalog.products.bundle-items') }}'" :active="true">
    <div slot="body">

        {!! view_render_event('bagisto.admin.catalog.product.edit_form_accordian.bundle.controls.before', ['product' => $product]) !!}

        {!! view_render_event('bagisto.admin.catalog.product.edit_form_accordian.bundle.controls.after', ['product' => $product]) !!}

    </div>
</accordian>