<div class="tabs">
    <ul>
        @if (request()->route()->getName() != 'admin.configuration.index')

            <?php $keys = explode('.', $menu->currentKey);  ?>


            @foreach(array_get($menu->items, implode('.children.', array_slice($keys, 0, 2)) . '.children') as $item)
                <li class="{{ $menu->getActive($item) }}">
                    <a href="{{ $item['url'] }}">
                        {{ $item['name'] }}
                    </a>
                </li>
            @endforeach

        @else

            @if (array_get($config->items, request()->route('slug') . '.children'))

                @foreach (array_get($config->items, request()->route('slug') . '.children') as $key => $item)

                    <li class="{{ $key == request()->route('slug2') ? 'active' : '' }}">
                        <a href="{{ route('admin.configuration.index', (request()->route('slug') . '/' . $key)) }}">
                            {{ $item['name'] }}
                        </a>
                    </li>

                @endforeach

            @endif

        @endif
    </ul>
</div>