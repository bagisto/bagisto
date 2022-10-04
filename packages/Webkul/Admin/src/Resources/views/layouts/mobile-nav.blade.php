@php

    $tree = \Webkul\Core\Tree::create();

    foreach (config('core') as $item) {
        $tree->add($item);
    }

    $tree->items = core()->sortItems($tree->items);

    $config = $tree;
@endphp

<mobile-nav></mobile-nav>

@push('scripts')
    <script type="text/x-template" id="mobile-nav-template">
        <div class="nav-container">

            <div class="nav-toggle"></div>

            <div class="overlay"></div>

            <div class="nav-top">
                <div class="pro-info">
                    <div class="profile-info-icon">
                        <span style="">{{ substr(auth()->guard('admin')->user()->name, 0, 1) }}</span>
                    </div>

                    <div class="profile-info-desc">
                        <div class="name">
                            {{ auth()->guard('admin')->user()->name }}
                        </div>

                        <div class="role">
                            {{ auth()->guard('admin')->user()->role['name'] }}
                        </div>
                    </div>
                    <div style="display:inline-block" @click="closeNavBar">
                        <span class="close"></span>
                    </div>
                </div>
            </div>

            <div class="nav-items">
                @foreach ($menu->items as $menuItem)
                    <div class="nav-item {{ $menu->getActive($menuItem) }}">
                        <a class="nav-tab-name" href="{{ $menuItem['key'] == 'dashboard' ? $menuItem['url'] : '#' }}">
                            <span class="icon-menu icon {{ $menuItem['icon-class'] }}"
                            style="margin-right:10px; display: inline-block;vertical-align: middle;transform: scale(0.8);"></span>

                            <span class="menu-label">{{ trans($menuItem['name']) }}</span>
                            @if(count($menuItem['children']) || $menuItem['key'] == 'configuration' )
                            <span class="icon arrow-icon"></span>
                            @endif
                        </a>
                        @if ($menuItem['key'] != 'configuration')
                            @if (count($menuItem['children']))
                            <ul>
                                @foreach ($menuItem['children'] as $subMenuItem)
                                    <li class="navbar-child {{ $menu->getActive($subMenuItem) }}">
                                    <a href="{{ count($subMenuItem['children']) ? current($subMenuItem['children'])['url'] : $subMenuItem['url'] }}">
                                            <span style="margin-left:47px">{{ trans($subMenuItem['name']) }}</span>
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                            @endif
                        @else
                            <ul>
                                @foreach ($config->items as $key => $item)
                                    <li class="navbar-child {{ $item['key'] == request()->route('slug') ? 'active' : '' }}">
                                        <a href="{{ route('admin.configuration.index', $item['key']) }}">
                                            <span style="margin-left:47px">{{ trans($item['name'] ?? '') }}</span>
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    </div>
                @endforeach
                <div class="nav-item">
                    <a class="nav-tab-name">
                        <span class="icon-menu icon accounts-icon"
                            style="margin-right:10px; display: inline-block;vertical-align: middle;transform: scale(0.8);"></span>
                        <span class="menu-label">{{ __('admin::app.layouts.account-title') }}</span>
                        <span class="icon arrow-icon"></span>
                    </a>
                    <ul>
                        <li class="navbar-child">
                            <a>
                                <span style="display:flex;justify-content:space-between;height:20px">
                                    <div style="margin-top:12px;margin-left:47px">
                                        <span>{{ __('admin::app.layouts.mode') }}</span>
                                    </div>
                                    <dark style="margin-left:13%"></dark>
                                </span>
                            </a>

                        </li>
                        <li  class="navbar-child">
                            <a href="{{ route('admin.account.edit') }}">
                                <span style="margin-left:47px">{{ __('admin::app.layouts.my-account') }}</span>
                            </a>
                        </li>
                        <li  class="navbar-child">
                            <a href="{{ route('admin.session.destroy') }}">
                                <span style="margin-left:47px">{{ __('admin::app.layouts.logout') }}</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </script>

    <script>
    Vue.component('mobile-nav', {

        template: '#mobile-nav-template',

        data: function() {
            return {
               openProfileNav: 0,
               openLocaleNav:0
            }
        },

        mounted(){
            const nav = document.querySelector(".nav-container");

            if (nav) {
                const toggle = nav.querySelector(".nav-toggle");

                if (toggle) {
                    toggle.addEventListener("click", () => {
                        if (nav.classList.contains("is-active")) {
                            nav.classList.remove("is-active");
                        } else {
                            nav.classList.add("is-active");
                        }
                    });

                    nav.addEventListener("blur", () => {
                        nav.classList.remove("is-active");
                    });
                }
            }

            document.querySelectorAll('.nav-tab-name').forEach(function(navItem) {
                navItem.addEventListener('click', function(item) {
                    var tabname = item.target.innerText;
                    if (! navItem.parentElement.classList.contains("pro-info") && tabname != 'Dashboard') {
                        navItem.parentElement.classList.toggle("active");
                        navItem.parentElement.children[1].classList.toggle("display-block");
                        navItem.children[2].classList.toggle("rotate-arrow-icon");
                    }

                });
            });
        },

        methods: {
           closeNavBar: function(){
                $('.nav-toggle').click();
           }
        }
    });
</script>
@endpush