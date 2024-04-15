<div class="tabs">
    @if ($items = Arr::get($menu->items, implode('.children.', array_slice(explode('.', $menu->currentKey), 0, 2)) . '.children'))
        <div class="mb-4 flex gap-4 border-b-2 pt-2 dark:border-gray-800 max-sm:hidden">
            @foreach ($items as $key => $item)
                <a href="{{ $item['url'] }}">
                    <div class="{{  $menu->getActive($item) ? "-mb-px border-blue-600 border-b-2 transition" : '' }} pb-3.5 px-2.5 text-base  font-medium text-gray-600 dark:text-gray-300 cursor-pointer">
                        @lang($item['name'])
                    </div>
                </a>
            @endforeach
        </div>
    @endif
</div>