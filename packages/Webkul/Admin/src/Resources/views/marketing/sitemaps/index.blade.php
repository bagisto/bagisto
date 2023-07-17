<x-admin::layouts>
    <div class="flex gap-[16px] justify-between items-center max-sm:flex-wrap">
        <p class="text-[20px] text-gray-800 font-bold">
            @lang('admin::app.marketing.sitemaps.create.title')
        </p>

        <div class="flex gap-x-[10px] items-center">
            @include('admin::marketing.sitemaps.create')
        </div>
    </div>
</x-admin::layouts>