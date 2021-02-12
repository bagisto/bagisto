<div class="navbar-top">
    <div class="navbar-top-left">
        <div class="brand-logo">
            <a href="{{ route('admin.dashboard.index') }}">
                @if (core()->getConfigData('general.design.admin_logo.logo_image', core()->getCurrentChannelCode()))
                    <img src="{{ \Illuminate\Support\Facades\Storage::url(core()->getConfigData('general.design.admin_logo.logo_image', core()->getCurrentChannelCode())) }}" alt="{{ config('app.name') }}" style="height: 40px; width: 110px;"/>
                @else
                    <img src="{{ asset('vendor/webkul/ui/assets/images/logo.png') }}" alt="{{ config('app.name') }}"/>
                @endif
            </a>
        </div>
    </div>

    <div class="navbar-top-right">
        <div class="profile">
            <span class="avatar">
            </span>

            <div class="profile-info">
                <div class="dropdown-toggle">
                    <div style="display: inline-block; vertical-align: middle;">
                        <span class="name">
                            {{ auth()->guard('admin')->user()->name }}
                        </span>

                        <span class="role">
                            {{ auth()->guard('admin')->user()->role['name'] }}
                        </span>
                    </div>
                    <i class="icon arrow-down-icon active"></i>
                </div>

                <div class="dropdown-list bottom-right">
                    <span class="app-version">{{ __('admin::app.layouts.app-version', ['version' => 'v' . config('app.version')]) }}</span>

                    <div class="dropdown-container">
                        <label>Account</label>
                        <ul>
                            <li>
                                <a href="{{ route('shop.home.index') }}" target="_blank">{{ __('admin::app.layouts.visit-shop') }}</a>
                            </li>
                            <li>
                                <a href="{{ route('admin.account.edit') }}">{{ __('admin::app.layouts.my-account') }}</a>
                            </li>
                            <li>
                                <a href="{{ route('admin.session.destroy') }}">{{ __('admin::app.layouts.logout') }}</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>