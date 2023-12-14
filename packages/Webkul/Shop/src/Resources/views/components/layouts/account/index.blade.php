<x-shop::layouts>
    <!-- Page Title -->
    <x-slot:title>
        {{ $title ?? '' }}
    </x-slot>

    <!-- Page Content -->
    <div class="container px-[60px] max-lg:px-[30px] max-sm:px-[15px]">
        <x-shop::layouts.account.breadcrumb />

        <div class="flex gap-10 items-start mt-[30px] max-lg:gap-5 max-md:grid">
            <x-shop::layouts.account.navigation />

            <div class="flex-auto">
                {{ $slot }}
            </div>
        </div>
    </div>
</x-shop::layouts>
