@inject ('toolbarHelper', 'Webkul\Product\Helpers\Toolbar')

{!! view_render_event('bagisto.shop.products.list.toolbar.before') !!}
    <div class="view-mode">

        @if (
            ! ($toolbarHelper->isModeActive('grid')
            || $toolbarHelper->isModeActive('list'))
        )
            <div class="rango-view-grid-container active">
                <span class="rango-view-grid fs24"></span>
            </div>
        @else
            @if ($toolbarHelper->isModeActive('grid'))
                <div class="rango-view-grid-container active">
                    <span class="rango-view-grid fs24"></span>
                </div>
            @else
                <div class="rango-view-grid-container">
                    <a href="{{ $toolbarHelper->getModeUrl('grid') }}" class="grid-view unset">
                        <span class="rango-view-grid fs24"></span>
                    </a>
                </div>
            @endif
        @endif

        @if ($toolbarHelper->isModeActive('list'))
            <div class="rango-view-list-container active">
                <span class="rango-view-list fs24"></span>
            </div>
        @else
            <div class="rango-view-list-container">
                <a
                    href="{{ $toolbarHelper->getModeUrl('list') }}"
                    class="list-view unset">
                    <span class="rango-view-list fs24"></span>
                </a>
            </div>
        @endif
    </div>

    <div class="sorter">
        <label>{{ __('shop::app.products.sort-by') }}</label>
        <i class="icon fs16 cell rango-arrow-down select-icon-margin down-icon-position"></i>
            <select class="selective-div" onchange="window.location.href = this.value">

                @foreach ($toolbarHelper->getAvailableOrders() as $key => $order)

                    <option value="{{ $toolbarHelper->getOrderUrl($key) }}" {{ $toolbarHelper->isOrderCurrent($key) ? 'selected' : '' }}>
                        {{ __('shop::app.products.' . $order) }}
                    </option>

                @endforeach

            </select>
    </div>

    <div class="limiter">
        <label>{{ __('shop::app.products.show') }}</label>
        <i class="icon fs16 cell rango-arrow-down select-icon-show-margin down-icon-position"></i>
        <select class="selective-div" onchange="window.location.href = this.value" style="width: 57px;">

            @foreach ($toolbarHelper->getAvailableLimits() as $limit)

                <option value="{{ $toolbarHelper->getLimitUrl($limit) }}" {{ $toolbarHelper->isLimitCurrent($limit) ? 'selected' : '' }}>
                    {{ $limit }}
                </option>

            @endforeach

        </select>
    </div>

{!! view_render_event('bagisto.shop.products.list.toolbar.after') !!}


<div class="responsive-layred-filter mb-20">
    {{-- <layered-navigation></layered-navigation> --}}
</div>