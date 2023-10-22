<div class="grid gap-[14px] justify-center justify-items-center py-[40px] px-[10px]">
    <img
        class="w-[120px] h-[120px] p-2 dark:invert dark:mix-blend-exclusion"
        src="{{ bagisto_asset('images/empty-placeholders/default-empty.svg') }}"
    >

    <div class="flex flex-col gap-[5px] items-center">

        <p class="text-[16px] text-gray-400 font-semibold">
            @lang('admin::app.reporting.empty.title')
        </p>
        
        <p class="text-gray-400">
            @lang('admin::app.reporting.empty.info')
        </p>
    </div>
</div>