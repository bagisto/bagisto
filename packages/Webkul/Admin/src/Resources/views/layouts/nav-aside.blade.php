@if(count($subMenus))
    <div class="aside-nav">
        <ul>
            @foreach($subMenus['items'] as $menuItem)
                <li class="{{ $menu->getActive($menuItem) }}">
                    <a href="{{ $menuItem['url'] }}">
                        {{ $menuItem['name'] }}

                        @if ($menu->getActive($menuItem))
                            <i class="angle-right-icon"></i>
                        @endif
                    </a>
                </li>
            @endforeach
        </ul>
    </div>
@endif