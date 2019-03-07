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

    <div class="aside-nav-toggle">
        <i class="angle-left-icon close-icon"></i>
    </div>
</div>

@push('scripts')
    <script>
        $(document).ready(function() {
            $(".close-icon").on('click', function(e){
                $('.content-wrapper').css("margin-left", "0px");
                $('.aside-nav').hide();
                $('.open').show();
            });

            $(".open-icon").on('click', function(e){
                $('.content-wrapper').css("margin-left", "305px");
                $('.aside-nav').show();
                $('.open').hide();
            });
        });
    </script>
@endpush