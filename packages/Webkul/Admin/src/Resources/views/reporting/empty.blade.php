<div class="grid gap-3.5 justify-center justify-items-center py-10 px-2.5">
    <img
        class="w-[120px] h-[120px] p-2 dark:invert dark:mix-blend-exclusion"
        src="{{ bagisto_asset('images/empty-placeholders/report-empty.svg') }}"
    >

    <div class="flex flex-col gap-1.5 items-center">

        <p class="text-base text-gray-400 font-semibold">
            @lang('admin::app.reporting.empty.title')
        </p>
        
        <p class="text-gray-400">
            @lang('admin::app.reporting.empty.info')
        </p>
    </div>
</div>