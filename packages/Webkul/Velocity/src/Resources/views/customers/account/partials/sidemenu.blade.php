<div class="customer-sidebar row">

    <div class="account-details col-12">
        <div class="customer-name col-12 text-uppercase">
            {{ substr(auth('customer')->user()->first_name, 0, 1) }}
        </div>
        <div class="col-12 customer-name-text text-capitalize text-break">{{ auth('customer')->user()->first_name . ' ' . auth('customer')->user()->last_name}}</div>
        <div class="customer-email col-12 text-break">{{ auth('customer')->user()->email }}</div>
    </div>

    @foreach ($menu->items as $menuItem)
        <ul type="none" class="navigation">
            @foreach ($menuItem['children'] as $subMenuItem)
                <li class="{{ $menu->getActive($subMenuItem) }}">
                    <a class="unset fw6" href="{{ $subMenuItem['url'] }}">
                        {{ trans($subMenuItem['name']) }}
                    </a>
                </li>
            @endforeach
        </ul>
    @endforeach
</div>

@push('css')
    <style type="text/css">
        .categories-sidebar-container {
            margin-bottom: 0px;
            min-height: 100vh;
        }
    </style>
@endpush
