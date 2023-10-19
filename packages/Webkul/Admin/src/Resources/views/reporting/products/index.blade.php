<x-admin::layouts>
    <x-slot:title>
        @lang('admin::app.reporting.products.index.title')
    </x-slot:title>

    {{-- Page Header --}}
    <div class="flex gap-[16px] justify-between items-center mb-[20px] max-sm:flex-wrap">
        <div class="grid gap-[6px]">
            <p class="pt-[6px] text-[20px] text-gray-800 dark:text-white font-bold leading-[24px]">
                @lang('admin::app.reporting.products.index.title')
            </p>
        </div>
    </div>

    {{-- Products Stats Vue Component --}}
    <div class="flex flex-col gap-[15px] flex-1 max-xl:flex-auto">
        <!-- Total Sold Quantities and Products Added to Wishlist Sections Container -->
        <div class="flex justify-between gap-[15px] flex-1 [&>*]:flex-1 max-xl:flex-auto">
            <!-- Total Sold Quantities Section -->
            @include('admin::reporting.products.sold-quantities')

            <!-- Products Added to Wishlist Section -->
            @include('admin::reporting.products.wishlist-products')
        </div>

        <!-- Top Selling Products By Revenue and Top Selling Products By Quantity Sections Container -->
        <div class="flex justify-between gap-[15px] flex-1 [&>*]:flex-1 max-xl:flex-auto">
            <!-- Top Selling Products By Revenue Section -->
            @include('admin::reporting.products.top-selling-by-revenue')

            <!-- Top Selling Products By Quantity Section -->
            @include('admin::reporting.products.top-selling-by-quantity')
        </div>

        <!-- Products With Most Reviews and Products Wiht Most Visits Sections Container -->
        <div class="flex justify-between gap-[15px] flex-1 [&>*]:flex-1 max-xl:flex-auto">
            <!-- Products With Most Reviews Section -->
            @include('admin::reporting.products.most-reviews')

            <!-- Products Wiht Most Visits Section -->
            @include('admin::reporting.products.most-visits')
        </div>
    </div>

    @pushOnce('scripts')
        <script type="module" src="{{ bagisto_asset('js/chart.js') }}"></script>
    @endPushOnce
</x-admin::layouts>
