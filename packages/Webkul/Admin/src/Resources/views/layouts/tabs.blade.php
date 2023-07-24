<div class="tabs">
    @if (request()->route()->getName() != 'admin.configuration.index')
        @if ($items = \Illuminate\Support\Arr::get($menu->items, implode('.children.', array_slice(explode('.', $menu->currentKey), 0, 2)) . '.children'))
            <div class="flex gap-[15px] pt-[8px] bg-[#F5F5F5] border-b-[2px] max-sm:hidden">
                @foreach ($items as $key => $item)
                    <a href="{{ $menu->getActive($item) }}">
                        <div class="{{ $isActive ? "mb-[-1px] border-blue-600 border-b-[2px] transition" : '' }} pb-[14px] px-[10px] text-[16px] font-medium text-gray-600 cursor-pointer">
                            @lang($item['name'])
                        </div>
                    </a>
                @endforeach
            </div>
        @endif
    @else
        @if ($items = \Illuminate\Support\Arr::get($config->items, request()->route('slug') . '.children'))
            <div class="flex gap-[15px] pt-[8px] bg-[#F5F5F5] border-b-[2px] max-sm:hidden">
                @foreach ($items as $key => $item)
                    <a href="{{ route('admin.configuration.index', (request()->route('slug') . '/' . $key)) }}">
                        <div class="{{ $key == request()->route('slug2') ? "mb-[-1px] border-blue-600 border-b-[2px] transition" : '' }} pb-[14px] px-[10px] text-[16px] font-medium text-gray-600 cursor-pointer">
                            @lang($item['name'])
                        </div>
                    </a>
                @endforeach
            </div>
        @endif
    @endif
</div>