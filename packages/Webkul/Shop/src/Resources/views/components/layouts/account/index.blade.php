<x-shop::layouts>
    <div class="container px-[60px] max-lg:px-[30px]">
        <x-shop::layouts.account.breadcrumb />

        <div class="flex gap-[40px] mt-[30px] items-start max-lg:gap-[20px]">
            <x-shop::layouts.account.navigation />

            <div class="flex-auto">
                {{ $slot }}
            </div>
        </div>
    </div>
</x-shop::layouts>
