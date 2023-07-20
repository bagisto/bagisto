@php
    $isConfigurationIndex = request()->route()->getName() === 'admin.configuration.index';
@endphp

<div class="tabs mb-[15px]">
    @php
        $items = $isConfigurationIndex
            ? \Illuminate\Support\Arr::get($config->items, request()->route('slug') . '.children')
            : \Illuminate\Support\Arr::get($menu->items, implode('.children.', array_slice(explode('.', $menu->currentKey), 0, 2)) . '.children');
    @endphp

    @if ($items)
        <div class="flex gap-[15px] pt-[8px] bg-[#F5F5F5] border-b-[2px] max-sm:hidden">
            @foreach ($items as $key => $item)
                @php
                    $url = $isConfigurationIndex
                        ? route('admin.configuration.index', (request()->route('slug') . '/' . $key))
                        : $item['url'];

                    $isActive = $isConfigurationIndex
                        ? request()->route('slug2') === $key
                        : $menu->getActive($item);
                @endphp

                <a href="{{ $url }}">
                    <div class="{{ $isActive ? "mb-[-1px] border-blue-600 border-b-[2px] transition" : '' }} pb-[14px] px-[10px] text-[16px] font-medium text-gray-600 cursor-pointer">
                        @lang($item['name'])
                    </div>
                </a>
            @endforeach
        </div>
    @endif
</div>