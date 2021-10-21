<div class="tabs">
    @if (request()->route()->getName() != 'admin.configuration.index')

        <?php $keys = explode('.', $menu->currentKey);  ?>


        @if ($items = \Illuminate\Support\Arr::get($menu->items, implode('.children.', array_slice($keys, 0, 2)) . '.children'))

            <ul>

                @foreach (\Illuminate\Support\Arr::get($menu->items, implode('.children.', array_slice($keys, 0, 2)) . '.children') as $item)

                    <li class="{{ $menu->getActive($item) }}">
                        <a href="{{ $item['url'] }}">
                            {{ trans($item['name']) }}
                        </a>
                    </li>

                @endforeach

            </ul>

        @endif

    @else

        @if ($items = \Illuminate\Support\Arr::get($config->items, request()->route('slug') . '.children'))

            <ul>

                @foreach ($items as $key => $item)

                    <li class="{{ $key == request()->route('slug2') ? 'active' : '' }}">
                        <a href="{{ route('admin.configuration.index', (request()->route('slug') . '/' . $key)) }}">
                            {{ trans($item['name']) }}
                        </a>
                    </li>

                @endforeach

            </ul>

        @endif

    @endif
</div>