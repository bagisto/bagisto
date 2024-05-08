@props([
    'item',
    'top' => false,
])
@if($item instanceof menu\Menu\MenuDivider)
    <li class="menu-inner-divider">
        {!! $item->label() ? "<span>{$item->label()}</span>" : '' !!}
    </li>
@else
    <li class="menu-inner-item {{ $item->isActive() ? '_is-active' : '' }}">
        <a
            href="{{ $item->url() }}" {!! $item->isBlank() ? 'target="_blank"' : '' !!}
            class="menu-inner-link"
            x-data="navTooltip"
            @mouseenter="toggleTooltip()"
        >
            @if($item->iconValue())
                {!! $item->getIcon(6) !!}
            @elseif(!$top)
                <span class="menu-inner-item-char">
                    {{ str($item->label())->limit(2) }}
                </span>
            @endif

            <span class="menu-inner-text">{{ $item->label() }}</span>

            @if($item->hasBadge() && $badge = $item->getBadge())
                <span class="menu-inner-counter">{{ $badge }}</span>
            @endif
        </a>
    </li>
@endif
