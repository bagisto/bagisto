<div class="responsive-side-menu" id="responsive-side-menu" style="display: none">
    {{ __('shop::app.customer.account.menu') }}
    <i class="icon icon-arrow-down right" id="down-icon"></i>
</div>


<div class="sidebar">
    @foreach ($menu->items as $menuItem)
        <div class="menu-block">
            <div class="menu-block-title">
                {{ trans($menuItem['name']) }}
            </div>

            <div class="menu-block-content">
                <ul class="menubar">
                    @foreach ($menuItem['children'] as $subMenuItem)
                        <li class="menu-item {{ $menu->getActive($subMenuItem) }}">
                            <a href="{{ $subMenuItem['url'] }}">
                                {{ trans($subMenuItem['name']) }}
                            </a>

                            <i class="icon angle-right-icon"></i>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endforeach
</div>

@push('scripts')
<script>
    $(document).ready(function(){
        var sideMenuTitle = document.getElementById("responsive-side-menu");
        var downIcon = document.getElementById("down-icon");
        var accountSideMenu = document.getElementsByClassName("account-side-menu");

        sideMenuTitle.addEventListener("click", function(){
            if (downIcon.className == 'icon icon-arrow-down right') {
                for(let i=0 ; i < accountSideMenu.length ; i++) {
                    accountSideMenu[i].style.display="block";
                }

                downIcon.classList.remove("icon","icon-arrow-down","right");
                downIcon.classList.add("icon","icon-arrow-up","right");
            }else {
                for(let i=0 ; i < accountSideMenu.length ; i++) {
                    accountSideMenu[i].style.display="none";
                }

                downIcon.classList.remove("icon","icon-arrow-up","right");
                downIcon.classList.add("icon","icon-arrow-down","right");
            }
        });
    });
</script>
@endpush