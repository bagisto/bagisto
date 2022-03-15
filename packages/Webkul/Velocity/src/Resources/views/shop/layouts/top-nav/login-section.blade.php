{!! view_render_event('bagisto.shop.layout.header.account-item.before') !!}

<div id="account">
    <div class="d-inline-block welcome-content dropdown-toggle">
        <i class="material-icons align-vertical-top">perm_identity</i>

        <span class="text-center">
            {{ __('velocity::app.header.welcome-message', [
                    'customer_name' => auth()->guard('customer')->user()
                        ? auth()->guard('customer')->user()->first_name
                        : trans('velocity::app.header.guest')
                    ]
                )
            }}
        </span>

        <span class="rango-arrow-down"></span>
    </div>

    @guest('customer')
        <div class="dropdown-list" style="width: 290px">
            <div class="modal-content dropdown-container">
                <div class="modal-header no-border pb0">
                    <label class="fs18 grey">{{ __('shop::app.header.title') }}</label>
                </div>

                <div class="fs14 content">
                    <p class="no-margin">{{ __('shop::app.header.dropdown-text') }}</p>
                </div>

                <div class="modal-footer">
                    <a href="{{ route('customer.session.index') }}" class="theme-btn fs14 fw6">
                        {{ __('shop::app.header.sign-in') }}
                    </a>

                    <a href="{{ route('customer.register.index') }}" class="theme-btn fs14 fw6">
                        {{ __('shop::app.header.sign-up') }}
                    </a>
                </div>
            </div>
        </div>
    @endguest

    @auth('customer')
        <div class="dropdown-list">
            <div class="dropdown-label">
                {{ auth()->guard('customer')->user()->first_name }}
            </div>

            <div class="dropdown-container">
                <ul type="none">
                    <li>
                        <a href="{{ route('customer.profile.index') }}" class="unset">{{ __('shop::app.header.profile') }}</a>
                    </li>

                    <li>
                        <a href="{{ route('customer.orders.index') }}" class="unset">{{ __('velocity::app.shop.general.orders') }}</a>
                    </li>

                    @php
                        $showCompare = core()->getConfigData('general.content.shop.compare_option') == "1" ? true : false;

                        $showWishlist = core()->getConfigData('general.content.shop.wishlist_option') == "1" ? true : false;
                    @endphp

                    @if ($showWishlist)
                        <li>
                            <a href="{{ route('customer.wishlist.index') }}" class="unset">{{ __('shop::app.header.wishlist') }}</a>
                        </li>
                    @endif

                    @if ($showCompare)
                        <li>
                            <a href="{{ route('velocity.customer.product.compare') }}" class="unset">{{ __('velocity::app.customer.compare.text') }}</a>
                        </li>
                    @endif

                    <li>
                        <form id="customerLogout" action="{{ route('customer.session.destroy') }}" method="POST">
                            @csrf

                            @method('DELETE')
                        </form>

                        <a
                            class="unset"
                            href="{{ route('customer.session.destroy') }}"
                            onclick="event.preventDefault(); document.getElementById('customerLogout').submit();">
                            {{ __('shop::app.header.logout') }}
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    @endauth
</div>

{!! view_render_event('bagisto.shop.layout.header.account-item.after') !!}
