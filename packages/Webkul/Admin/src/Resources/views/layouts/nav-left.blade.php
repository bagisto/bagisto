<div class="navbar-left">
    {{-- button for toggling aside nav --}}
    <span class="toggle-aside-nav" id="randomButton" style="position: absolute; left: 78px; top: 50px;">
        <i class="icon accordian-up-icon"></i>
    </span>

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