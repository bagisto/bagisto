{!! view_render_event('bagisto.shop.components.layouts.header.desktop.bottom.before') !!}

<div class="flex min-h-[50px] w-full justify-between bg-pelorous-500 px-[60px] max-1180:px-8">
    <!--
        This section will provide categories for the first, second, and third levels. If
        additional levels are required, users can customize them according to their needs.
    -->
    <!-- Left Nagivation Section -->
    <div class="flex items-center gap-x-10 max-[1180px]:gap-x-5">
        {!! view_render_event('bagisto.shop.components.layouts.header.desktop.bottom.category.before') !!}

        <x-shop::layouts.header.desktop.categories />

        {!! view_render_event('bagisto.shop.components.layouts.header.desktop.bottom.category.after') !!}
    </div>

    <!-- Right Nagivation Section -->
    <div class="flex items-center gap-x-9 max-[1100px]:gap-x-6 max-lg:gap-x-8">

        {!! view_render_event('bagisto.shop.components.layouts.header.desktop.bottom.search_bar.before') !!}

        <!-- Search Bar Container -->
        <div class="relative w-full">
            <form
                action="{{ route('shop.search.index') }}"
                class="flex max-w-[445px] min-w-[250px] items-center"
                role="search"
            >
                <label
                    for="organic-search"
                    class="sr-only"
                >
                    @lang('shop::app.components.layouts.header.search')
                </label>

                <button type="submit">
                    <span class="icon-search absolute top-2.5 flex items-center text-xl ltr:right-3 rtl:left-3 cursor-pointer"></span>
                </button>

                <input
                    type="text"
                    name="query"
                    value="{{ request('query') }}"
                    class="block w-full border border-transparent bg-zinc-100 pl-5 pr-10 py-3 text-xs font-medium text-gray-900 transition-all hover:border-gray-400 focus:border-gray-400"
                    placeholder="@lang('shop::app.components.layouts.header.search-text')"
                    aria-label="@lang('shop::app.components.layouts.header.search-text')"
                    aria-required="true"
                    required
                >

                <button
                    id="submit_search"
                    type="submit"
                    class="hidden"
                    aria-label="@lang('shop::app.components.layouts.header.submit')"
                >
                </button>
            </form>
        </div>

        {!! view_render_event('bagisto.shop.components.layouts.header.desktop.bottom.search_bar.after') !!}
    </div>
</div>

{!! view_render_event('bagisto.shop.components.layouts.header.desktop.bottom.after') !!}
