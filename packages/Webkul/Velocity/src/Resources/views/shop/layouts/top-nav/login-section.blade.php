{!! view_render_event('bagisto.shop.layout.header.account-item.before') !!}
    <login-header></login-header>
{!! view_render_event('bagisto.shop.layout.header.account-item.after') !!}

<script type="text/x-template" id="login-header-template">
    <div class="dropdown">
        <div id="account">

            <div class="welcome-content pull-right" @click="togglePopup">
                <i class="material-icons align-vertical-top">perm_identity</i>
                <span class="text-center">
                    @guest('customer')
                        {{ __('velocity::app.header.welcome-message', ['customer_name' => trans('velocity::app.header.guest')]) }}!
                    @endguest

                    @auth('customer')
                        {{ __('velocity::app.header.welcome-message', ['customer_name' => auth()->guard('customer')->user()->first_name]) }}
                    @endauth
                </span>
                <span class="select-icon rango-arrow-down"></span>
            </div>
        </div>

        <div class="account-modal sensitive-modal hide mt5">
            <!--Content-->
                @guest('customer')
                    <div class="modal-content">
                        <!--Header-->
                        <div class="modal-header no-border pb0">
                            <label class="fs18 grey">{{ __('shop::app.header.title') }}</label>

                            <button type="button" class="close disable-box-shadow" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true" class="white-text fs20" @click="togglePopup">×</span>
                            </button>
                        </div>

                        <!--Body-->
                        <div class="pl10 fs14">
                            <p>{{ __('shop::app.header.dropdown-text') }}</p>
                        </div>

                        <!--Footer-->
                        <div class="modal-footer">
                            <div>
                                <a href="{{ route('customer.session.index') }}">
                                    <button
                                        type="button"
                                        class="theme-btn fs14 fw6">

                                        {{ __('shop::app.header.sign-in') }}
                                    </button>
                                </a>
                            </div>

                            <div>
                                <a href="{{ route('customer.register.index') }}">
                                    <button
                                        type="button"
                                        class="theme-btn fs14 fw6">
                                        {{ __('shop::app.header.sign-up') }}
                                    </button>
                                </a>
                            </div>
                        </div>
                    </div>
                @endguest

                @auth('customer')
                    <div class="modal-content customer-options">
                        <div class="customer-session">
                            <label class="">
                                {{ auth()->guard('customer')->user()->first_name }}
                            </label>
                        </div>

                        <ul type="none">
                            <li>
                                <a href="{{ route('customer.profile.index') }}" class="unset">{{ __('shop::app.header.profile') }}</a>
                            </li>

                            <li>
                                <a href="{{ route('customer.wishlist.index') }}" class="unset">{{ __('shop::app.header.wishlist') }}</a>
                            </li>

                            <li>
                                <a href="{{ route('shop.checkout.cart.index') }}" class="unset">{{ __('shop::app.header.cart') }}</a>
                            </li>

                            <li>
                                <a href="{{ route('customer.session.destroy') }}" class="unset">{{ __('shop::app.header.logout') }}</a>
                            </li>
                        </ul>
                    </div>
                @endauth
            <!--/.Content-->
        </div>
    </div>
</script>

@push('scripts')
    <script type="text/javascript">

        Vue.component('login-header', {
            template: '#login-header-template',

            methods: {
                togglePopup: function (event) {
                    let accountModal = this.$el.querySelector('.account-modal');
                    let modal = $('#cart-modal-content')[0];

                    if (modal)
                        modal.classList.add('hide');

                    accountModal.classList.toggle('hide');

                    event.stopPropagation();
                }
            }
        })

    </script>
@endpush

