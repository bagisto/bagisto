<div class="navbar-top">
    <div class="navbar-top-left">
        <div class="brand-logo">
            <a href="{{ route('admin.dashboard.index') }}">
                <img src="{{ asset('vendor/webkul/ui/assets/images/logo.png') }}" alt="Bagisto"/>
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
                            Administrator
                        </span>
                    </div>
                    <i class="icon arrow-down-icon active"></i>
                </div>

                <div class="dropdown-list bottom-right">
                    <div class="dropdown-container">
                        <label>Account</label>
                        <ul>
                            <li>
                                <a href="{{ route('admin.account.edit') }}">My Account</a>
                            </li>
                            <li>
                                <a href="{{ route('admin.session.destroy') }}">Logout</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>