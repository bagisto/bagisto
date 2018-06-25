<div class="navbar-left">
    <ul class="menubar">
        @foreach($menu->items as $menu)
            <li class="menu-item {{ $menu['active'] ? 'active' : '' }}">
                <a href="{{ $menu['url'] }}">
                    <span class="icon {{ $menu['icon-class'] }}">
                    </span>
                    {{ $menu['name'] }}
                </a>
            </li>
        @endforeach
    </ul>
</div>