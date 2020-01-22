@inject ('toolbarHelper', 'Webkul\Product\Helpers\Toolbar')

{!! view_render_event('bagisto.shop.products.list.toolbar.before') !!}
    <toolbar-component></toolbar-component>
{!! view_render_event('bagisto.shop.products.list.toolbar.after') !!}

@push('scripts')
    <script type="text/x-template" id="toolbar-template">
        <div class="toolbar-wrapper" v-if='!isMobile()'>
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
                <select class="selective-div border-normal" onchange="window.location.href = this.value">

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
                <select class="selective-div border-normal" onchange="window.location.href = this.value" style="width: 57px;">

                    @foreach ($toolbarHelper->getAvailableLimits() as $limit)

                        <option value="{{ $toolbarHelper->getLimitUrl($limit) }}" {{ $toolbarHelper->isLimitCurrent($limit) ? 'selected' : '' }}>
                            {{ $limit }}
                        </option>

                    @endforeach

                </select>
            </div>
        </div>

        <div class="toolbar-wrapper row" v-else>
            <div class="col-4">
                <i class="material-icons">filter_list</i>
                <span>Filter</span>
            </div>

            <div class="col-4">
                <i class="material-icons">sort_by_alpha</i>
                <span>Sort By</span>
            </div>

            <div class="col-4">
                <i class="material-icons">view_module
                </i>
                <span>View</span>
            </div>
        </div>
    </script>

    <script type="text/javascript">
        (() => {
            Vue.component('toolbar-component', {
                template: '#toolbar-template'
            })
        })()
    </script>
@endpush
