<div class="aside-nav">
    <ul>
        @foreach($menu['items'] as $menu)
            <li class="{{ $menu['active'] ? 'active' : '' }}">
                <a href="{{ $menu['url'] }}">
                    {{ $menu['name'] }}

                    @if ($menu['active'])
                        <i class="angle-right-icon"></i>
                    @endif
                </a>
            </li>
        @endforeach
    </ul>
</div>