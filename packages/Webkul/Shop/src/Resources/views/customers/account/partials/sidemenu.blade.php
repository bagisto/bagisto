<ul class="account-side-menu">
    @foreach($menu->items as $key=>$value)

        <li><a href="{{ $value['url'] }}">{{ $value['name'] }}</a></li>

    @endforeach
</ul>
