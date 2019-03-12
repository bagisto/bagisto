<div class="aside-nav">
    <ul>
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

    <div class="close-nav-aside">
        <i class="icon angle-left-icon close-icon"></i>
    </div>

    <div class="open-nav-aside">
        <i class="icon angle-left-icon close-icon"></i>
    </div>
</div>

@push('scripts')
    <script>
        $(document).ready(function() {
            $(".close-nav-aside").on('click', function(e) {
                $('.content-wrapper').css("margin-left", "0px");
                $('.aside-nav').css('display', 'none');
                $('.open-nav-aside').css('display', 'block');
                $('.close-nav-aside').css('display', 'none');
            });

            $(".open-nav-aside").on('click', function(e) {
                $('.content-wrapper').css("margin-left", "305px");
                $('.aside-nav').css('display', '');
                $('.open-nav-aside').css('display', 'none');
                $('.close-nav-aside').css('display', 'block');
            });
        });
    </script>
@endpush