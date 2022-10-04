{!! view_render_event('bagisto.shop.layout.header.account-item.before') !!}

<div id="account">
    <div class="d-inline-block welcome-content dropdown-toggle">
        @if (auth()->guard('customer')->user() && auth()->guard('customer')->user()->image)
            <i class="align-vertical-top"><img class= "profile-small-icon" src="{{ auth('customer')->user()->image_url }}" alt="{{ auth('customer')->user()->first_name }}"/></i>
        @else
            <i class="material-icons align-vertical-top">perm_identity</i>
        @endif

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
                    <a href="{{ route('shop.customer.session.index') }}" class="theme-btn fs14 fw6">
                        {{ __('shop::app.header.sign-in') }}
                    </a>

                    <a href="{{ route('shop.customer.register.index') }}" class="theme-btn fs14 fw6">
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
                        <a href="{{ route('shop.customer.profile.index') }}" class="unset">{{ __('shop::app.header.profile') }}</a>
                    </li>

                    <li>
                        <a href="{{ route('shop.customer.orders.index') }}" class="unset">{{ __('velocity::app.shop.general.orders') }}</a>
                    </li>

                    @if ((bool) core()->getConfigData('general.content.shop.wishlist_option'))
                        <li>
                            <a href="{{ route('shop.customer.wishlist.index') }}" class="unset">{{ __('shop::app.header.wishlist') }}</a>
                        </li>
                    @endif

                    @if ((bool) core()->getConfigData('general.content.shop.compare_option'))
                        <li>
                            <a href="{{ route('velocity.customer.product.compare') }}" class="unset">{{ __('velocity::app.customer.compare.text') }}</a>
                        </li>
                    @endif

                    <li>
                        <form id="customerLogout" action="{{ route('shop.customer.session.destroy') }}" method="POST">
                            @csrf

                            @method('DELETE')
                        </form>

                        <a
                            class="unset"
                            href="{{ route('shop.customer.session.destroy') }}"
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
