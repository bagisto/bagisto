<div class="tabs">
    @if ($items = Arr::get($menu->items, implode('.children.', array_slice(explode('.', $menu->currentKey), 0, 2)) . '.children'))
        <div class="flex gap-[15px] mb-[15px] pt-[8px] border-b-[2px] max-sm:hidden dark:border-gray-800">
            @foreach ($items as $key => $item)
                <a href="{{ $item['url'] }}">
                    <div class="{{  $menu->getActive($item) ? "mb-[-1px] border-blue-600 border-b-[2px] transition" : '' }} pb-[14px] px-[10px] text-[16px] font-medium text-gray-600 dark:text-gray-300 cursor-pointer">
                        @lang($item['name'])
                    </div>
                </a>
            @endforeach
        </div>
    @endif
</div>