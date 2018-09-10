@if(count($tabs))
    <div class="tabs">
        <ul>
            @foreach($tabs['items'] as $tab)
                <li class="{{ $menu->getActive($tab) }}">
                    <a href="{{ $tab['url'] }}">
                        {{ $tab['name'] }}
                    </a>
                </li>
            @endforeach
        </ul>
    </div>
@endif