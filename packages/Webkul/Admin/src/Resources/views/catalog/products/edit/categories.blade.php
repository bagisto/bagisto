{!! view_render_event('bagisto.admin.catalog.product.edit.form.categories.before', ['product' => $product]) !!}

{{-- Panel --}}
<div class="p-[16px] bg-white rounded-[4px] box-shadow">
    {{-- Panel Header --}}
    <p class="flex justify-between text-[16px] text-gray-800 font-semibold mb-[16px]">
        @lang('admin::app.catalog.products.edit.categories.title')
    </p>

    {!! view_render_event('bagisto.admin.catalog.product.edit.form.categories.controls.before', ['product' => $product]) !!}

    {{-- Panel Content --}}
    <div class="mb-[20px] text-[14px] text-gray-600">
        <x-admin::tree.view
            input-type="checkbox"
            name-field="categories"
            id-field="id"
            value-field="id"
            :items="json_encode($categories)"
            :value="json_encode($product->categories->pluck('id'))"
            behavior="no"
            :fallback-locale="config('app.fallback_locale')"
        >
        </x-admin::tree.view>
    </div>

    {!! view_render_event('bagisto.admin.catalog.product.edit.form.categories.controls.after', ['product' => $product]) !!}
</div>

{!! view_render_event('bagisto.admin.catalog.product.edit.form.categories.after', ['product' => $product]) !!}