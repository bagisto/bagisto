<ul class="account-side-menu">
    <div class="side-menu-title" id="side-menu-title">
        Menu
    </div>

    @foreach($menu->items as $key=>$value)

        <li><a href="{{ $value['url'] }}">{{ $value['name'] }}</a></li>

    @endforeach
</ul>


