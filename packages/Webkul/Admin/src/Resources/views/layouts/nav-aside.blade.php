<div class="aside-nav">
    <ul>
        {{-- <li class="slider-aside">
            <span class="icon cross-icon" style="height: 64px; width: 64px; background: red;"></span>
        </li> --}}
        @if (request()->route()->getName() != 'admin.configuration.index')
            <?php $keys = explode('.', $menu->currentKey);  ?>

            @foreach (array_get($menu->items, current($keys) . '.children') as $item)
                <li class="{{ $menu->getActive($item) }}">
                    <a href="{{ $item['url'] }}">
                        {{ trans($item['name']) }}

                        @if ($menu->getActive($item))
                            <i class="angle-right-icon"></i>
                        @endif
                    </a>
                </li>
            @endforeach
        @else
            @foreach ($config->items as $key => $item)
                <li class="{{ $item['key'] == request()->route('slug') ? 'active' : '' }}">
                    <a href="{{ route('admin.configuration.index', $item['key']) }}">
                        {{ isset($item['name']) ? trans($item['name']) : '' }}

                        @if ($item['key'] == request()->route('slug'))
                            <i class="angle-right-icon"></i>
                        @endif
                    </a>
                </li>
            @endforeach
        @endif
    </ul>
</div>
{{-- @push('scripts')
    <script type="text/javascript">
        var turned = false;
        $(document).ready(function() {
            if(turned == false) {
                $('.slider-aside').on('click', function() {
                    $('.aside-nav').css('display', 'none');

                    $('.content-wrapper').css('margin-left', '0px');

                    turned = true;
                });
            } else {
                $('.slider-aside').on('click', function() {
                    $('.aside-nav').css('display', '');

                    $('.content-wrapper').css('margin-left', '305px');

                    turned = false;
                });
            }

        });

    </script>
@endpush --}}