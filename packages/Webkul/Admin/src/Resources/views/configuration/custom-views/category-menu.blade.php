<!-- Category Layouts -->
<div class="flex gap-4">
    <!-- Default Menu -->
    <x-admin::modal class="[&>*>*>.box-shadow]:!max-w-[1000px]">
        <x-slot:toggle>
            <button type="button">
                <p class="text-sm text-blue-600 transition-all hover:underline">
                    @lang('admin::app.configuration.index.general.design.menu-category.preview-default')
                </p>
            </button>
        </x-slot>

        <x-slot:header>
            <p class="text-base font-semibold text-gray-800 dark:text-white">
                @lang('admin::app.configuration.index.general.design.menu-category.default')
            </p>
        </x-slot>

        <x-slot:content class="!border-none">
            <img
                class="border border-gray-200"
                src="{{ bagisto_asset('images/configuration/default-menu.svg') }}"
                alt="{{ trans('admin::app.configuration.index.general.design.menu-category.default') }}"
            >
        </x-slot>
    </x-admin::modal>

    <!-- Sidebar Menu -->
    <x-admin::modal class="[&>*>*>.box-shadow]:!max-w-[1000px]">
        <x-slot:toggle>
            <button type="button">
                <p class="text-sm text-blue-600 transition-all hover:underline">
                    @lang('admin::app.configuration.index.general.design.menu-category.preview-sidebar')
                </p>
            </button>
        </x-slot>

        <x-slot:header>
            <p class="text-base font-semibold text-gray-800 dark:text-white">
                @lang('admin::app.configuration.index.general.design.menu-category.sidebar')
            </p>
        </x-slot>

        <x-slot:content class="!border-none">
            <img 
                class="border border-gray-200"
                src="{{ bagisto_asset('images/configuration/side-bar-menu.svg') }}"
                alt="{{ trans('admin::app.configuration.index.general.design.menu-category.sidebar') }}"
            >
        </x-slot>
    </x-admin::modal>
</div>