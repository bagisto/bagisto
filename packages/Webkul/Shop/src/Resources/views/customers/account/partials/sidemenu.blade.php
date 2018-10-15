{{--  <div class="side-menu-title mb-20" id="side-menu-title">
    <strong>Menu</strong>
    <i class="icon icon-arrow-down right" id="down-icon"></i>
</div>  --}}

<ul class="account-side-menu">
    @foreach($menu->items as $key=>$value)

        <li><a href="{{ $value['url'] }}">{{ $value['name'] }}</a></li>

    @endforeach
</ul>


@push('scripts')

<script>

    $(document).ready(function(){

        var sideMenuTitle = document.getElementById("side-menu-title");
        var downIcon = document.getElementById("down-icon");
        var accountSideMenu = document.getElementsByClassName("account-side-menu");

        sideMenuTitle.addEventListener("click", function(){
            if(downIcon.className == 'icon icon-arrow-down right'){
                for(let i=0 ; i < accountSideMenu.length ; i++){
                    accountSideMenu[i].style.display="block";
                }
                downIcon.classList.remove("icon","icon-arrow-down","right");
                downIcon.classList.add("icon","icon-arrow-up","right");
            }else{
                for(let i=0 ; i < accountSideMenu.length ; i++){
                    accountSideMenu[i].style.display="none";
                }
                downIcon.classList.remove("icon","icon-arrow-up","right");
                downIcon.classList.add("icon","icon-arrow-down","right");
            }
        });

    });

</script>

@endpush


