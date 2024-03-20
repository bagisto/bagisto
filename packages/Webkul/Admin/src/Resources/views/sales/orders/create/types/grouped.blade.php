{!! view_render_event('bagisto.admin.sales.order.create.types.grouped.before') !!}

<v-product-grouped-options
    :errors="errors"
    :options="selectedProductOptions"
></v-product-grouped-options>

{!! view_render_event('bagisto.admin.sales.order.create.types.grouped.after') !!}