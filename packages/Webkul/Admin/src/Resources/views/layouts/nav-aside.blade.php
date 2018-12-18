<div class="aside-nav">
    <ul>
        @if (request()->route()->getName() != 'admin.configuration.index')

            <?php $keys = explode('.', $menu->currentKey);  ?>

            @foreach(array_get($menu->items, current($keys) . '.children') as $item)
                <li class="{{ $menu->getActive($item) }}">
                    <a href="{{ $item['url'] }}">
                        {{ $item['name'] }}

                        @if ($menu->getActive($item))
                            <i class="angle-right-icon"></i>
                        @endif
                    </a>
                </li>
            @endforeach

        @else

            @foreach($config->items as $key => $item)

                <li class="{{ $item['key'] == request()->route('slug') ? 'active' : '' }}">
                    <a href="{{ route('admin.configuration.index', $item['key']) }}">
                        {{ isset($item['name']) ? $item['name'] : '' }}

                        @if ($item['key'] == request()->route('slug'))
                            <i class="angle-right-icon"></i>
                        @endif
                    </a>
                </li>

            @endforeach

        @endif
    </ul>
</div>