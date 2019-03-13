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

    {{-- <div class="close-nav-aside">
        <i class="icon angle-left-icon close-icon"></i>
    </div> --}}
</div>

@push('scripts')
    <script>
        window.onload = function () {
            st = $(document).scrollTop();
            windowHeight = $(window).height();
            documentHeight = $(document).height();
            menubarHeight = $('ul.menubar').height();

            if(menubarHeight > windowHeight) {
                remainent = documentHeight - menubarHeight;
                travelRatio = remainent / (documentHeight - windowHeight);
            }

            console.log(travelRatio);

            $(document).scroll(function() {
                st = $(document).scrollTop();

                if(menubarHeight > windowHeight && st < menubarHeight / 2) {
                    marginTopForMenubar = travelRatio * st;

                    $('.navbar-left').css('top', + 60 - marginTopForMenubar);
                }
            });
        }
    </script>
@endpush