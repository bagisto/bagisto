<div class="navbar-left">
    <ul class="menubar">
        @foreach($menu->items as $menuItem)
            <li class="menu-item {{ $menu->getActive($menuItem) }}">
                <a href="{{ $menuItem['url'] }}">
                    <span class="icon {{ $menuItem['icon-class'] }}">
                    </span>
                    {{ $menuItem['name'] }}
                </a>
            </li>
        @endforeach
    </ul>
</div>