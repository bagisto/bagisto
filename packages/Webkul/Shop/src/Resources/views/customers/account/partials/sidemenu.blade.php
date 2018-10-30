<div class="responsive-side-menu" id="responsive-side-menu" style="display: none">
    {{ __('shop::app.customer.account.menu') }}
    <i class="icon icon-arrow-down right" id="down-icon"></i>
</div>

<ul class="account-side-menu">
    @foreach($menu->items as $menuItem)
        <li class="menu-item {{ $menu->getActive($menuItem) }}">
            <a href="{{ $menuItem['url'] }}">
                {{ $menuItem['name'] }}
            </a>

            <i class="icon angle-right-icon"></i>
        </li>
    @endforeach
</ul>

@push('scripts')
<script>
    $(document).ready(function(){
        var sideMenuTitle = document.getElementById("responsive-side-menu");
        var downIcon = document.getElementById("down-icon");
        var accountSideMenu = document.getElementsByClassName("account-side-menu");

        sideMenuTitle.addEventListener("click", function(){
            if(downIcon.className == 'icon icon-arrow-down right') {
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