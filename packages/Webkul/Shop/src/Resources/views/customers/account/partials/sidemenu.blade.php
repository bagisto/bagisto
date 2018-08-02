<ul class="account-side-menu">
    @foreach($menu->items as $key=>$value)
        <li class="{{ request()->is('*/account/profile') ? 'active' : '' }}"><a href="{{ $value['url'] }}">{{ $value['name'] }}</a></li>
    @endforeach
</ul>
