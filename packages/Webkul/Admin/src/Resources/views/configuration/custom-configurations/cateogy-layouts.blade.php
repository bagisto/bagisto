<div class="flex gap-4">
    <x-admin::modal>
        <x-slot:toggle>
            <button type="button">
                <p class="text-sm text-blue-600 transition-all hover:underline">@lang('Peview Default Menu')</p>
            </button>
        </x-slot>

        <x-slot:header>
            <p class="text-base font-semibold text-gray-800 dark:text-white">Category Layout One</p>
        </x-slot>

        <x-slot:content>
            <img 
                src="{{ bagisto_asset('images/configuration/default-menu.svg') }}"
                alt="Category Layouts"
            >
        </x-slot>
    </x-admin::modal>

    <x-admin::modal>
        <x-slot:toggle>
            <button type="button">
                <p class="text-sm text-blue-600 transition-all hover:underline">@lang('Preview Sidebar Menu')</p>
            </button>
        </x-slot>

        <x-slot:header>
            <p class="text-base font-semibold text-gray-800 dark:text-white">Category Layout Two</p>
        </x-slot>

        <x-slot:content>
            <img 
                src="{{ bagisto_asset('images/configuration/side-bar-menu.svg') }}"
                alt="Category Layouts"
            >
        </x-slot>
    </x-admin::modal>
</div>