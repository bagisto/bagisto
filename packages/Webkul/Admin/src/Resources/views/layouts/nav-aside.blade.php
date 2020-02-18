<div class="aside-nav" id="aside-nav">
    <ul>
        @if (request()->route()->getName() != 'admin.configuration.index')
            <?php $keys = explode('.', $menu->currentKey);  ?>

            @if(isset($keys) && strlen($keys[0]))
            @foreach (\Illuminate\Support\Arr::get($menu->items, current($keys) . '.children') as $item)
                <li class="{{ $menu->getActive($item) }}">
                    <a href="{{ $item['url'] }}">
                        {{ trans($item['name']) }}

                        @if ($menu->getActive($item))
                            <i class="angle-right-icon"></i>
                        @endif
                    </a>
                </li>
            @endforeach
            @endif
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
        <i class="icon angle-left-icon close-icon" id="sideBar" onClick="closeSideBar()"></i>
    </div>
</div>

@push('scripts')
    <script>
        function closeSideBar()
        {
            var asideNav = document.getElementById("aside-nav").style.width;
            var element = document.getElementById("sideBar");
            var content = document.getElementsByClassName("content-wrapper")[0];

            if (asideNav == '0px') {
                content.style.marginLeft = "280px";
                element.classList.add("angle-left-icon");
                element.classList.remove("angle-right-icon");
                document.getElementById("aside-nav").style.width = "280px";
            } else {
                content.style.marginLeft = "0px";
                element.classList.remove("angle-left-icon");
                element.classList.add("angle-right-icon");
                document.getElementById("aside-nav").style.width = "0px";
            }

            document.getElementById("sideBar").style.float = "left";
        }
    </script>
@endpush
