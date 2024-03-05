{!! view_render_event('bagisto.admin.catalog.product.edit.form.images.before', ['product' => $product]) !!}

<div class="relative p-4 bg-white dark:bg-gray-900 rounded box-shadow">
    <!-- Panel Header -->
    <div class="flex gap-5 justify-between mb-4">
        <div class="flex flex-col gap-2">
            <p class="text-base text-gray-800 dark:text-white font-semibold">
                @lang('admin::app.catalog.products.edit.images.title')
            </p>

            <p class="text-xs text-gray-500 dark:text-gray-300 font-medium">
                @lang('admin::app.catalog.products.edit.images.info')
            </p>
        </div>
    </div>

    <!-- Image Blade Component -->
    <x-admin::media.images
        name="images[files]"
        allow-multiple="true"
        show-placeholders="true"
        :uploaded-images="$product->images"
    />
</div>

{!! view_render_event('bagisto.admin.catalog.product.edit.form.images.after', ['product' => $product]) !!}