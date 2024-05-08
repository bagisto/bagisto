@props([
    'data' => $_data ?? [],
    'top' => $isTop ?? false,
    'isScrollTo' => $isScrollTo ?? true,
])
@if($data)
    <ul {{ $attributes->class(['menu-inner']) }}
        @if(!$top && $isScrollTo)
            x-init="$nextTick(() => document.querySelector('.menu-inner-item._is-active')?.scrollIntoView())"
        @endif
    >
        @foreach($data as $item)
            @if($item->isGroup())
                <x-menu::menu.group
                    :item="$item"
                    :top="$top"
                />
            @else
                <x-menu::menu.item
                    :item="$item"
                    :top="$top"
                />
            @endif


        @endforeach
    </ul>
@endif

{{ $slot ?? '' }}
