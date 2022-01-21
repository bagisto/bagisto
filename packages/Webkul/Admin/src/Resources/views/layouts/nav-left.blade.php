@php

    $tree = \Webkul\Core\Tree::create();

    foreach (config('core') as $item) {
        $tree->add($item);
    }

    $tree->items = core()->sortItems($tree->items);

    $config = $tree;

    $allLocales = core()->getAllLocales()->pluck('name', 'code');

    $currentLocaleCode = core()->getRequestedLocaleCode('admin_locale');
@endphp

<div class="navbar-left" v-bind:class="{'open': isMenuOpen}">

    <navbar-left></navbar-left>

    <nav-slide-button id="nav-expand-button" icon-class="accordian-right-icon"></nav-slide-button>
</div>

@push('scripts')
    <script type="text/x-template" id="navbar-left-template">
        
        {{-- left menu bar --}}
        <ul class="menubar">
            @foreach ($menu->items as $menuItem)
            <li class="menu-item {{ $menu->getActive($menuItem) }}">
                <a class="menubar-ancor"  href="{{ $menuItem['key'] == 'dashboard' ? $menuItem['url'] : '#' }}">
                    <span class="icon-menu icon {{ $menuItem['icon-class'] }}"></span>

                    <span class="menu-label">{{ trans($menuItem['name']) }}</span>

                    @if(count($menuItem['children']) || $menuItem['key'] == 'configuration' )
                        <span class="icon arrow-icon {{ $menu->getActive($menuItem) == 'active' ? 'rotate-arrow-icon' : '' }} "></span>
                    @endif
                </a>
                @if ($menuItem['key'] != 'configuration')
                    @if (count($menuItem['children']))
                        <ul class="sub-menubar">
                            @foreach ($menuItem['children'] as $subMenuItem)
                                <li class="sub-menu-item {{ $menu->getActive($subMenuItem) }}">
                                    <a href="{{ count($subMenuItem['children']) ? current($subMenuItem['children'])['url'] : $subMenuItem['url'] }}">
                                        <span class="menu-label">{{ trans($subMenuItem['name']) }}</span>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                @else
                    <ul class="sub-menubar">
                        @foreach ($config->items as $key => $item)
                            <li class="sub-menu-item {{ $item['key'] == request()->route('slug') ? 'active' : '' }}">
                                <a href="{{ route('admin.configuration.index', $item['key']) }}">                                  
                                    <span class="menu-label"> {{ isset($item['name']) ? trans($item['name']) : '' }}</span>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </li>
            @endforeach
        </ul>
    </script>

    <script>
    Vue.component('navbar-left', {

        template: '#navbar-left-template',

        data: function() {
            return {
               openProfileNav: 0,
               openLocaleNav:0
            }
        },

        mounted(){
            
            $('.menu-item').click(function(){
                if(! this.classList.contains('active')){
                    $('.menu-item').removeClass('active');

                    if (this.children[0].children[1].innerHTML != 'Dashboard') {
                        this.classList.toggle('active');
                        this.children[0].children[2].classList.toggle("rotate-arrow-icon");
                    }  
                }                               
            }); 
        },
    });
</script>
@endpush