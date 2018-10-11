<ul class="account-side-menu">
    <div class="side-menu-title-active" id="side-menu-title" onclick="sayHello()">
        Menu
    </div>

    @foreach($menu->items as $key=>$value)

        <li><a href="{{ $value['url'] }}">{{ $value['name'] }}</a></li>

    @endforeach
</ul>


@push('scripts')

<script>

    function sayHello(){

        alert('hello');

        var sideMenuTitle = document.getElementById("side-menu-title");

        if(sideMenuTitle == 'side-menu-title-active'){
            console.log('dffd');
            sideMenuTitle.classList.remove("side-menu-title-active");
            sideMenuTitle.classList.add("side-menu-title");
        }else{
            sideMenuTitle.classList.remove("side-menu-title");
            sideMenuTitle.classList.add("side-menu-title-active");

        }

    }

</script>

@endpush


