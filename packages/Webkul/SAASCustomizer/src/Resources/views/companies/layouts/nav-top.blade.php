<div class="navbar-top">
    <div class="navbar-top-left">
        <div class="brand-logo">
            <a href="{{ route('admin.dashboard.index') }}">
                <img src="{{ asset('vendor/webkul/ui/assets/images/logo.png') }}" alt="Bagisto"/>
            </a>
        </div>
    </div>

    <div class="navbar-top-right">
        <div class="profile" style="padding-top: 5px;">
            @auth('super-admin')
                <div class="profile-info">
                    <div class="dropdown-toggle">
                        <div style="display: inline-block; vertical-align: middle;">
                            <span class="name">
                                Super Admin
                            </span>
                        </div>
                        <i class="icon arrow-down-icon active"></i>
                    </div>

                    <div class="dropdown-list bottom-right">
                        <div class="dropdown-container">
                            <label>Account</label>
                            <ul>
                                <li>
                                    <a href="{{ route('super.session.destroy') }}">{{ trans('admin::app.layouts.logout') }}</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            @endauth
        </div>
    </div>
</div>