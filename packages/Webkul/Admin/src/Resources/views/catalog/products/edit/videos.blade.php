{!! view_render_event('bagisto.admin.catalog.product.edit.form.videos.before', ['product' => $product]) !!}

<div class="box-shadow relative rounded bg-white p-4 dark:bg-gray-900">
    <!-- Panel Header -->
    <div class="mb-4 flex justify-between gap-5">
        <div class="flex flex-col gap-2">
            <p class="text-base font-semibold text-gray-800 dark:text-white">
                @lang('admin::app.catalog.products.edit.videos.title')
            </p>

            <p class="text-xs font-medium text-gray-500 dark:text-gray-300">
                @lang('admin::app.catalog.products.edit.videos.info', ['size' => core()->getMaxUploadSize()])
            </p>
        </div>
    </div>

    <!-- Video Blade Component -->
    <x-admin::media.videos
        name="videos[files]"
        :allow-multiple="true"
        :uploaded-videos="$product->videos"
    />

    <x-admin::form.control-group.error control-name='videos.files[0]' />
</div>

{!! view_render_event('bagisto.admin.catalog.product.edit.form.videos.after', ['product' => $product]) !!}