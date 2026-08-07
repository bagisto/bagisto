@php
    /**
     * Where this import's images come from. Three mutually exclusive methods,
     * driven by one radio group so the chosen one is explicit on the record
     * rather than guessed from which field happens to be filled in.
     *
     * Each method keeps its own value in its own column, so reopening an import
     * shows the method it was saved with and the value that went with it — and
     * choosing a different method never overwrites the other's.
     */
    $imageSource = old('image_source') ?? ($import->image_source ?? 'directory');

    $archiveName = $import->images_archive_name ?? null;

    $archiveImages = $uploadedImagesCount ?? 0;

    /**
     * Only importers that carry images ship a sample archive, so the link is
     * dropped entirely when none of them define one.
     */
    $hasSampleImagesZip = collect(config('importers'))->contains(
        fn ($importer) => ! empty($importer['sample_images_zip_path'])
    );

    /**
     * Only some kinds of import have images at all — customers and tax rates have
     * none — and the type is chosen on this same form, so the panel is shown or
     * hidden against the field rather than against the import being edited.
     */
    $imageTypes = collect(config('importers'))
        ->keys()
        ->filter(fn ($type) => \Webkul\DataTransfer\Helpers\Import::typeSupportsImages($type))
        ->values();
@endphp

<!-- Product Images Panel -->
<div
    class="box-shadow rounded bg-white p-4 dark:bg-gray-900"
    id="import-images-panel"
    data-image-types="{{ $imageTypes->toJson() }}"
>
    <p class="text-base font-semibold text-gray-800 dark:text-white">
        @lang('admin::app.settings.data-transfer.imports.images.title')
    </p>

    <p class="mb-4 mt-1 text-xs text-gray-600 dark:text-gray-300">
        @lang('admin::app.settings.data-transfer.imports.images.info')
    </p>

    <div class="grid gap-2.5">
        <!-- Each method is one label holding a screen-reader-only radio, so the whole card is clickable while the control stays keyboard reachable. The radio's siblings react to it with `peer-checked:` and the card itself with `has-[:checked]:` — which is why the dot, the text and the revealed field are all direct children of the label rather than nested: `peer-checked:` only reaches siblings. -->

        <!-- Method: links in the file -->
        <label class="relative grid cursor-pointer gap-1 rounded border border-gray-300 p-4 transition-all ltr:pl-12 rtl:pr-12 hover:border-gray-400 has-[:checked]:border-blue-600 has-[:checked]:bg-blue-50 dark:border-gray-700 dark:hover:border-gray-600 dark:has-[:checked]:border-blue-400 dark:has-[:checked]:bg-gray-800">
            <input
                type="radio"
                name="image_source"
                value="url"
                class="peer sr-only"
                @checked($imageSource === 'url')
            />

            <span class="icon-radio-normal absolute top-4 text-2xl text-gray-500 peer-checked:icon-radio-selected peer-checked:text-blue-600 ltr:left-4 rtl:right-4 dark:text-gray-400"></span>

            <span class="flex flex-wrap items-center gap-2">
                <span class="font-medium text-gray-800 dark:text-white">
                    @lang('admin::app.settings.data-transfer.imports.images.url-title')
                </span>

                <span class="label-active">
                    @lang('admin::app.settings.data-transfer.imports.images.recommended')
                </span>
            </span>

            <span class="text-xs text-gray-600 dark:text-gray-300">
                @lang('admin::app.settings.data-transfer.imports.images.url-info')
            </span>
        </label>

        <!-- Method: upload an archive -->
        <label class="relative grid cursor-pointer gap-1 rounded border border-gray-300 p-4 transition-all ltr:pl-12 rtl:pr-12 hover:border-gray-400 has-[:checked]:border-blue-600 has-[:checked]:bg-blue-50 dark:border-gray-700 dark:hover:border-gray-600 dark:has-[:checked]:border-blue-400 dark:has-[:checked]:bg-gray-800">
            <input
                type="radio"
                name="image_source"
                value="upload"
                class="peer sr-only"
                @checked($imageSource === 'upload')
            />

            <span class="icon-radio-normal absolute top-4 text-2xl text-gray-500 peer-checked:icon-radio-selected peer-checked:text-blue-600 ltr:left-4 rtl:right-4 dark:text-gray-400"></span>

            <span class="font-medium text-gray-800 dark:text-white">
                @lang('admin::app.settings.data-transfer.imports.images.upload-title')
            </span>

            <span class="text-xs text-gray-600 dark:text-gray-300">
                @lang('admin::app.settings.data-transfer.imports.images.upload-info')
            </span>

            <span class="mt-2 hidden peer-checked:block">
                <x-admin::form.control-group class="!mb-0">
                    <x-admin::form.control-group.control
                        type="file"
                        name="upload_images"
                        accept=".zip"
                        :label="trans('admin::app.settings.data-transfer.imports.images.upload-title')"
                    />

                    <!-- The sample archive holds exactly the files the sample sheet names, so the two can be downloaded and uploaded together to see the method work end to end. -->
                    @if ($hasSampleImagesZip)
                        <a
                            :href="'{{ route('admin.settings.data_transfer.imports.download_sample_zip', ['type' => ':type:']) }}'.replace(':type:', $refs['importType']?.value)"
                            target="_blank"
                            class="mt-2 inline-block cursor-pointer text-sm text-blue-600 transition-all hover:underline"
                        >
                            @lang('admin::app.settings.data-transfer.imports.images.download-sample-zip')
                        </a>
                    @endif

                    <!-- An empty file field reads as "no images uploaded", so an import that already has some says so — otherwise editing anything else on the form looks like it lost them. -->
                    @if ($archiveImages)
                        <p class="mt-2 text-xs text-gray-600 dark:text-gray-300">
                            @if ($archiveName)
                                @lang('admin::app.settings.data-transfer.imports.images.current-archive', [
                                    'name'  => $archiveName,
                                    'count' => $archiveImages,
                                ])
                            @else
                                @lang('admin::app.settings.data-transfer.imports.images.current-images', [
                                    'count' => $archiveImages,
                                ])
                            @endif
                        </p>

                        <p class="mt-1 text-xs text-gray-600 dark:text-gray-300">
                            @lang('admin::app.settings.data-transfer.imports.images.replace-archive')
                        </p>
                    @endif

                    <x-admin::form.control-group.error control-name="upload_images" />
                </x-admin::form.control-group>
            </span>
        </label>

        <!-- Method: a directory placed on the server -->
        <label class="relative grid cursor-pointer gap-1 rounded border border-gray-300 p-4 transition-all ltr:pl-12 rtl:pr-12 hover:border-gray-400 has-[:checked]:border-blue-600 has-[:checked]:bg-blue-50 dark:border-gray-700 dark:hover:border-gray-600 dark:has-[:checked]:border-blue-400 dark:has-[:checked]:bg-gray-800">
            <input
                type="radio"
                name="image_source"
                value="directory"
                class="peer sr-only"
                @checked($imageSource === 'directory')
            />

            <span class="icon-radio-normal absolute top-4 text-2xl text-gray-500 peer-checked:icon-radio-selected peer-checked:text-blue-600 ltr:left-4 rtl:right-4 dark:text-gray-400"></span>

            <span class="font-medium text-gray-800 dark:text-white">
                @lang('admin::app.settings.data-transfer.imports.images.directory-title')
            </span>

            <span class="text-xs text-gray-600 dark:text-gray-300">
                @lang('admin::app.settings.data-transfer.imports.images.directory-info')
            </span>

            <span class="mt-2 hidden peer-checked:block">
                <x-admin::form.control-group class="!mb-0">
                    <x-admin::form.control-group.control
                        type="text"
                        name="images_directory_path"
                        :value="old('images_directory_path') ?? ($import->images_directory_path ?? '')"
                        :placeholder="trans('admin::app.settings.data-transfer.imports.images.directory-placeholder')"
                    />

                    <p class="mt-2 text-xs text-gray-600 dark:text-gray-300">
                        @lang('admin::app.settings.data-transfer.imports.images.directory-hint')
                    </p>

                    <p class="mt-2 text-xs text-gray-600 dark:text-gray-300">
                        @lang('admin::app.settings.data-transfer.imports.images.directory-example')
                    </p>

                    <x-admin::form.control-group.error control-name="images_directory_path" />
                </x-admin::form.control-group>
            </span>
        </label>
    </div>
</div>

@pushOnce('scripts')
    <script>
        (function () {
            /**
             * Both elements are looked up on every call, and the listener sits on
             * the document rather than the select. This script is parsed before the
             * root Vue app mounts, and mounting replaces these nodes — anything
             * held on to beforehand is detached by the time the field is touched.
             */
            function sync () {
                var panel = document.getElementById('import-images-panel');
                var type = document.getElementById('import-type');

                if (! panel || ! type) {
                    return;
                }

                var withImages = JSON.parse(panel.dataset.imageTypes || '[]');

                panel.classList.toggle('hidden', withImages.indexOf(type.value) === -1);
            }

            document.addEventListener('change', function (event) {
                if (event.target && event.target.id === 'import-type') {
                    sync();
                }
            });

            window.addEventListener('load', sync);
        })();
    </script>
@endPushOnce
