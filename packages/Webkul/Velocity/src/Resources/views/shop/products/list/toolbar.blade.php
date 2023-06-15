@inject ('toolbarHelper', 'Webkul\Product\Helpers\Toolbar')

{!! view_render_event('bagisto.shop.products.list.toolbar.before') !!}
    <toolbar-component></toolbar-component>
{!! view_render_event('bagisto.shop.products.list.toolbar.after') !!}

@push('scripts')
    <script type="text/x-template" id="toolbar-template">
        <div class="toolbar-wrapper" v-if='currentScreen > 992'>
            <div class="view-mode">
                @php
                  $viewOption = $toolbarHelper->getViewOption();
                @endphp

                <div class="rango-view-grid-container {{ $viewOption === 'grid' ? 'active' : '' }}">
                    <a href="{{ $toolbarHelper->getModeUrl('grid') }}" class="grid-view unset" aria-label="Grid">
                        <span class="rango-view-grid fs24"></span>
                    </a>
                </div>
                <div class="rango-view-list-container {{ $viewOption === 'list' ? 'active' : '' }}" aria-label="List">
                    <a
                        href="{{ $toolbarHelper->getModeUrl('list') }}"
                        class="list-view unset">
                        <span class="rango-view-list fs24"></span>
                    </a>
                </div>
            </div>

            <div class="sorter">
                <label>{{ __('shop::app.products.sort-by') }}</label>

                <select class="selective-div border-normal styled-select" onchange="window.location.href = this.value" aria-label="Sort By">
                    @foreach ($toolbarHelper->getAvailableOrders() as $key => $order)
                        <option value="{{ $toolbarHelper->getOrderUrl($key) }}" {{ $toolbarHelper->isOrderCurrent($key) ? 'selected' : '' }}>
                            {{ __('shop::app.products.' . $order) }}
                        </option>
                    @endforeach
                </select>

                <div class="select-icon-container">
                    <span class="select-icon rango-arrow-down"></span>
                </div>
            </div>

            <div class="limiter">
                <label>{{ __('shop::app.products.show') }}</label>

                <select class="selective-div border-normal styled-select" onchange="window.location.href = this.value" style="width: 57px;" aria-label="Show">

                    @foreach ($toolbarHelper->getAvailableLimits() as $limit)

                        <option value="{{ $toolbarHelper->getLimitUrl($limit) }}" {{ $toolbarHelper->isLimitCurrent($limit) ? 'selected' : '' }}>
                            {{ $limit }}
                        </option>

                    @endforeach

                </select>

                <div class="select-icon-container">
                    <span class="select-icon rango-arrow-down"></span>
                </div>
            </div>
        </div>

        <div class="toolbar-wrapper row col-12 remove-padding-margin" v-else>
            <div
                v-if="layeredNavigation"
                class="nav-container scrollable"
                style="
                    z-index: 1000;
                    color: black;
                    position: relative;
                ">
                <div class="header drawer-section">
                    <i class="material-icons" @click="toggleLayeredNavigation">keyboard_backspace</i>

                    <span class="fs24 fw6">
                        {{ __('velocity::app.shop.general.filter') }}
                    </span>
                    <span class="float-right link-color" @click="toggleLayeredNavigation">
                        {{ __('velocity::app.responsive.header.done') }}
                    </span>
                </div>

                @if (request()->route()->getName() != 'velocity.search.index')
                    @include ('shop::products.list.layered-navigation')
                @endif
            </div>

            <div class="col-2 col-filter" @click="toggleLayeredNavigation({event: $event, actionType: 'open'})">
                <a class="unset">
                    <i class="material-icons">filter_list</i>
                    <span>{{ __('velocity::app.shop.general.filter') }}</span>
                </a>
            </div>

            <div class="col-5 col-order">
                <div class="sorter" id="sort-by">

                    <select class="selective-div border-normal styled-select" onchange="window.location.href = this.value">
                        @foreach ($toolbarHelper->getAvailableOrders() as $key => $order)
                            <option value="{{ $toolbarHelper->getOrderUrl($key) }}" {{ $toolbarHelper->isOrderCurrent($key) ? 'selected' : '' }}>
                                {{ __('shop::app.products.' . $order) }}
                            </option>
                        @endforeach
                    </select>

                    <div class="select-icon-container">
                        <span class="select-icon rango-arrow-down"></span>
                    </div>
                </div>
            </div>

            <div class="col-3 col-count">
                <div class="limiter" id="limit-by">

                    <select class="selective-div border-normal styled-select" onchange="window.location.href = this.value" style="width: 57px;" aria-label="Show">

                        @foreach ($toolbarHelper->getAvailableLimits() as $limit)

                            <option value="{{ $toolbarHelper->getLimitUrl($limit) }}" {{ $toolbarHelper->isLimitCurrent($limit) ? 'selected' : '' }}>
                                {{ $limit }}
                            </option>

                        @endforeach

                    </select>

                    <div class="select-icon-container">
                        <span class="select-icon rango-arrow-down"></span>
                    </div>
                </div>
            </div>

            <div class="col-2 col-view">
                @php
                    $isList = $toolbarHelper->isModeActive('list');
                @endphp

                <a
                    class="unset"
                    href="{{
                        $isList
                        ? $toolbarHelper->getModeUrl('grid')
                        : $toolbarHelper->getModeUrl('list')
                    }}">

                    <i class="material-icons">
                        @if ($isList) list @else view_module @endif
                    </i>
                    <span>{{ __('velocity::app.shop.general.view') }}</span>
                </a>
            </div>
        </div>
    </script>

    <script type="text/javascript">
        (() => {
            Vue.component('toolbar-component', {
                template: '#toolbar-template',
                data() {
                    return {
                        layeredNavigation: false,
                        currentScreen : window.innerWidth,
                    }
                },

                created() {
                    window.addEventListener('resize', this.handleResize);
                },

                destroyed() {
                    window.removeEventListener('resize', this.handleResize);
                },

                watch: {
                    layeredNavigation(value) {
                        if (value) {
                            document.body.classList.add('open-hamburger');
                        } else {
                            document.body.classList.remove('open-hamburger');
                        }
                    }
                },

                methods: {
                    toggleLayeredNavigation({event, actionType}) {
                        this.layeredNavigation = !this.layeredNavigation;
                    },

                    handleResize() {
                        this.currentScreen = window.innerWidth;
                    }
                }
            })
        })()
    </script>
@endpush