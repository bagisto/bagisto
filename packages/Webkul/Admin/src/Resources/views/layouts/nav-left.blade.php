<div class="navbar-left">

    {{-- button for expanding nav bar --}}
    <nav-slide-button id="nav-expand-button" style="display: none;" @if (core()->getCurrentLocale() && core()->getCurrentLocale()->direction == 'rtl') icon-class="accordian-left-icon" :direction="'rtl'" @else icon-class="accordian-right-icon" @endif></nav-slide-button>

    {{-- left menu bar --}}
    <ul class="menubar">
        @foreach ($menu->items as $menuItem)
            <li class="menu-item {{ $menu->getActive($menuItem) }}">
                <a href="{{ count($menuItem['children']) ? current($menuItem['children'])['url'] : $menuItem['url'] }}">
                    <span class="icon {{ $menuItem['icon-class'] }}"></span>

                    <span>{{ trans($menuItem['name']) }}</span>
                </a>
            </li>
        @endforeach
    </ul>

</div>