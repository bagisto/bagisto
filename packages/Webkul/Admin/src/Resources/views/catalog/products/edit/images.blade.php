{!! view_render_event('bagisto.admin.catalog.product.edit.form.images.before', ['product' => $product]) !!}

<div class="box-shadow relative rounded-sm bg-white p-4 dark:bg-gray-900">
    <!-- Panel Header -->
    <div class="mb-4 flex justify-between gap-5">
        <div class="flex flex-col gap-2">
            <p class="text-base font-semibold text-gray-800 dark:text-white">
                @lang('admin::app.catalog.products.edit.images.title')
            </p>

            <p class="text-xs font-medium text-gray-500 dark:text-gray-300">
                @lang('admin::app.catalog.products.edit.images.info')
            </p>
        </div>
    </div>

    <!-- Image Blade Component -->
    <x-admin::media.images
        name="images[files]"
        accepted-types=".bmp,.jpeg,.jpg,.png,.webp"
        meta-name="images[meta]"
        allow-multiple="true"
        show-placeholders="true"
        enable-seo="true"
        :uploaded-images="$product->images"
    />

    @foreach ($errors->get('images.meta.*') as $metaMessages)
        @foreach ($metaMessages as $metaMessage)
            <p class="mt-1 text-xs italic text-red-600">{{ $metaMessage }}</p>
        @endforeach
    @endforeach
</div>

{!! view_render_event('bagisto.admin.catalog.product.edit.form.images.after', ['product' => $product]) !!}