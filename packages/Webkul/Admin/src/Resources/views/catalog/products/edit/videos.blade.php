{!! view_render_event('bagisto.admin.catalog.product.edit.form.videos.before', ['product' => $product]) !!}

<div class="relative p-4 bg-white dark:bg-gray-900 rounded box-shadow">
    <!-- Panel Header -->
    <div class="flex gap-5 justify-between mb-4">
        <div class="flex flex-col gap-2">
            <p class="text-base text-gray-800 dark:text-white font-semibold">
                @lang('admin::app.catalog.products.edit.videos.title')
            </p>

            <p class="text-xs text-gray-500 dark:text-gray-300 font-medium">
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