<x-admin::layouts>
    <x-slot:title>
        @lang('admin::app.catalog.products.edit.title')
    </x-slot>

    {!! view_render_event('bagisto.admin.catalog.product.edit.before', ['product' => $product]) !!}

    <x-admin::form
        method="PUT"
        enctype="multipart/form-data"
    >
        {!! view_render_event('bagisto.admin.catalog.product.edit.actions.before', ['product' => $product]) !!}

        <!-- Page Header -->
        <div class="grid gap-2.5">
            <div class="flex gap-4 justify-between items-center max-sm:flex-wrap">
                <div class="grid gap-1.5">
                    <p class="text-xl text-gray-800 dark:text-white font-bold leading-6">
                        @lang('admin::app.catalog.products.edit.title')
                    </p>
                </div>

                <div class="flex gap-x-2.5 items-center">
                    <!-- Back Button -->
                    <a
                        href="{{ route('admin.catalog.products.index') }}"
                        class="transparent-button hover:bg-gray-200 dark:hover:bg-gray-800 dark:text-white"
                    >
                        @lang('admin::app.account.edit.back-btn')
                    </a>

                    <!-- Preview Button -->
                    @if (
                        $product->status
                        && $product->visible_individually
                        && $product->url_key
                    )
                        <a
                            href="{{ route('shop.product_or_category.index', $product->url_key) }}"
                            class="secondary-button"
                            target="_blank"
                        >
                            @lang('admin::app.catalog.products.edit.preview')
                        </a>
                    @endif

                    <!-- Save Button -->
                    <button class="primary-button">
                        @lang('admin::app.catalog.products.edit.save-btn')
                    </button>
                </div>
            </div>
        </div>

        @php
            $channels = core()->getAllChannels();

            $currentChannel = core()->getRequestedChannel();

            $currentLocale = core()->getRequestedLocale();
        @endphp

        <!-- Channel and Locale Switcher -->
        <div class="flex  gap-4 justify-between items-center mt-7 max-md:flex-wrap">
            <div class="flex gap-x-1 items-center">
                <!-- Channel Switcher -->
                <x-admin::dropdown :class="$channels->count() <= 1 ? 'hidden' : ''">
                    <!-- Dropdown Toggler -->
                    <x-slot:toggle>
                        <button
                            type="button"
                            class="transparent-button px-1 py-1.5 hover:bg-gray-200 dark:hover:bg-gray-800 focus:bg-gray-200 dark:focus:bg-gray-800 dark:text-white"
                        >
                            <span class="icon-store text-2xl"></span>
                            
                            {{ $currentChannel->name }}

                            <input type="hidden" name="channel" value="{{ $currentChannel->code }}"/>

                            <span class="icon-sort-down text-2xl"></span>
                        </button>
                    </x-slot>

                    <!-- Dropdown Content -->
                    <x-slot:content class="!p-0">
                        @foreach ($channels as $channel)
                            <a
                                href="?{{ Arr::query(['channel' => $channel->code, 'locale' => $currentLocale->code]) }}"
                                class="flex gap-2.5 px-5 py-2 text-base cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-950 dark:text-white"
                            >
                                {{ $channel->name }}
                            </a>
                        @endforeach
                    </x-slot>
                </x-admin::dropdown>

                <!-- Locale Switcher -->
                <x-admin::dropdown :class="$currentChannel->locales->count() <= 1 ? 'hidden' : ''">
                    <!-- Dropdown Toggler -->
                    <x-slot:toggle>
                        <button
                            type="button"
                            class="transparent-button px-1 py-1.5 hover:bg-gray-200 dark:hover:bg-gray-800 focus:bg-gray-200 dark:focus:bg-gray-800 dark:text-white"
                        >
                            <span class="icon-language text-2xl"></span>

                            {{ $currentLocale->name }}
                            
                            <input type="hidden" name="locale" value="{{ $currentLocale->code }}"/>

                            <span class="icon-sort-down text-2xl"></span>
                        </button>
                    </x-slot>

                    <!-- Dropdown Content -->
                    <x-slot:content class="!p-0">
                        @foreach ($currentChannel->locales->sortBy('name') as $locale)
                            <a
                                href="?{{ Arr::query(['channel' => $currentChannel->code, 'locale' => $locale->code]) }}"
                                class="flex gap-2.5 px-5 py-2 text-base cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-950 dark:text-white {{ $locale->code == $currentLocale->code ? 'bg-gray-100 dark:bg-gray-950' : ''}}"
                            >
                                {{ $locale->name }}
                            </a>
                        @endforeach
                    </x-slot>
                </x-admin::dropdown>
            </div>
        </div>

        {!! view_render_event('bagisto.admin.catalog.product.edit.actions.after', ['product' => $product]) !!}

        <!-- body content -->
        {!! view_render_event('bagisto.admin.catalog.product.edit.form.before', ['product' => $product]) !!}

        <div class="flex gap-2.5 mt-3.5 max-xl:flex-wrap">
            @foreach ($product->attribute_family->attribute_groups->groupBy('column') as $column => $groups)
                {!! view_render_event('bagisto.admin.catalog.product.edit.form.column_' . $column . '.before', ['product' => $product]) !!}

                <div class="flex flex-col gap-2 @if ($column == 1) flex-1 max-xl:flex-auto @elseif ($column == 2) w-[360px] max-w-full max-sm:w-full @endif">
                    @foreach ($groups as $group)
                        @php
                            $customAttributes = $product->getEditableAttributes($group);
                        @endphp

                        @if (count($customAttributes))
                            {!! view_render_event('bagisto.admin.catalog.product.edit.form.' . $group->code . '.before', ['product' => $product]) !!}

                            <div class="relative p-4 bg-white dark:bg-gray-900 rounded box-shadow">
                                <p class="text-base text-gray-800 dark:text-white font-semibold mb-4">
                                    {{ $group->name }}
                                </p>

                                @if ($group->code == 'meta_description')
                                    <!-- SEO Title & Description Blade Componnet -->
                                    <x-admin::seo />
                                @endif

                                @foreach ($customAttributes as $attribute)
                                    {!! view_render_event('bagisto.admin.catalog.product.edit.form.' . $group->code . '.controls.before', ['product' => $product]) !!}

                                    <x-admin::form.control-group class="last:!mb-0">
                                        <x-admin::form.control-group.label>
                                            {!! $attribute->admin_name . ($attribute->is_required ? '<span class="required"></span>' : '') !!}

                                            @if (
                                                $attribute->value_per_channel
                                                && $channels->count() > 1
                                            )
                                                <span class="px-1 py-0.5 bg-gray-100 border border-gray-200 rounded text-[10px] text-gray-600 font-semibold leading-normal">
                                                    {{ $currentChannel->name }}
                                                </span>
                                            @endif

                                            @if ($attribute->value_per_locale)
                                                <span class="px-1 py-0.5 bg-gray-100 border border-gray-200 rounded text-[10px] text-gray-600 font-semibold leading-normal">
                                                    {{ $currentLocale->name }}
                                                </span>
                                            @endif
                                        </x-admin::form.control-group.label>

                                        @include ('admin::catalog.products.edit.controls', [
                                            'attribute' => $attribute,
                                            'product'   => $product,
                                        ])
            
                                        <x-admin::form.control-group.error :control-name="$attribute->code . (in_array($attribute->type, ['multiselect', 'checkbox']) ? '[]' : '')" />
                                    </x-admin::form.control-group>

                                    {!! view_render_event('bagisto.admin.catalog.product.edit.form.' . $group->code . '.controls.before', ['product' => $product]) !!}
                                @endforeach

                                @includeWhen($group->code == 'price', 'admin::catalog.products.edit.price.group')

                                @includeWhen(
                                    $group->code == 'inventories' && ! $product->getTypeInstance()->isComposite(),
                                    'admin::catalog.products.edit.inventories'
                                )
                            </div>

                            {!! view_render_event('bagisto.admin.catalog.product.edit.form.' . $group->code . '.after', ['product' => $product]) !!}
                        @endif
                    @endforeach

                    @if ($column == 1)
                        <!-- Images View Blade File -->
                        @include('admin::catalog.products.edit.images')

                        <!-- Videos View Blade File -->
                        @include('admin::catalog.products.edit.videos')

                        <!-- Product Type View Blade File -->
                        @includeIf('admin::catalog.products.edit.types.' . $product->type)

                        <!-- Related, Cross Sells, Up Sells View Blade File -->
                        @include('admin::catalog.products.edit.links')

                        <!-- Include Product Type Additional Blade Files If Any -->
                        @foreach ($product->getTypeInstance()->getAdditionalViews() as $view)
                            @includeIf($view)
                        @endforeach
                    @else
                        <!-- Categories View Blade File -->
                        @include('admin::catalog.products.edit.categories')
                    @endif
                </div>

                {!! view_render_event('bagisto.admin.catalog.product.edit.form.column_' . $column . '.after', ['product' => $product]) !!}
            @endforeach
        </div>

        {!! view_render_event('bagisto.admin.catalog.product.edit.form.after', ['product' => $product]) !!}
    </x-admin::form>

    {!! view_render_event('bagisto.admin.catalog.product.edit.after', ['product' => $product]) !!}
</x-admin::layouts>