<div class="responsive-side-menu" id="responsive-side-menu" style="display: none">
    Menu
    <i class="icon icon-arrow-down right" id="down-icon"></i>
</div>

<ul class="account-side-menu">
    @foreach($menu->items as $menuItem)
        <li class="menu-item {{ $menu->getActive($menuItem) }}">
            <a href="{{ $menuItem['url'] }}">
                {{ $menuItem['name'] }}
            </a>

            <i class="icon angle-right-icon"></i>
        </li>
    @endforeach
</ul>