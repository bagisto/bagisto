@php
    $orderStatusMessages = [
        'pending' => trans('admin::app.notification.order-status-messages.pending'),
        'canceled'=> trans('admin::app.notification.order-status-messages.canceled'),
        'closed' => trans('admin::app.notification.order-status-messages.closed'),
        'completed'=> trans('admin::app.notification.order-status-messages.completed'),
        'processing' => trans('admin::app.notification.order-status-messages.processing'),
        'pending_payment' => trans('admin::app.notification.order-status-messages.pending_payment')
    ];
    $allLocales = core()->getAllLocales()->pluck('name', 'code');
@endphp

<div class="navbar-top">
    <div class="navbar-top-left">
        @include ('admin::layouts.mobile-nav')

        <div class="brand-logo">
            <a href="{{ route('admin.dashboard.index') }}">
                @if (core()->getConfigData('general.design.admin_logo.logo_image', core()->getCurrentChannelCode()))
                    <img src="{{ \Illuminate\Support\Facades\Storage::url(core()->getConfigData('general.design.admin_logo.logo_image', core()->getCurrentChannelCode())) }}" alt="{{ config('app.name') }}" style="height: 40px; width: 110px;"/>
                @else
                    <default-image
                        light-theme-image-url="{{ asset('vendor/webkul/ui/assets/images/logo.png') }}"
                        dark-theme-image-url="{{ asset('vendor/webkul/ui/assets/images/logo_light.png') }}"
                    ></default-image>
                @endif
            </a>
        </div>
    </div>

    <div class="navbar-top-right">
        <div class="profile">
            <span class="avatar">
            </span>

            <div class="store">
                <div>
                    <a  href="{{ route('shop.home.index') }}" target="_blank" style="display: inline-block; vertical-align: middle;">
                        <span class="icon store-icon" data-toggle="tooltip" data-placement="bottom" title="{{ __('admin::app.layouts.visit-shop') }}"></span>
                    </a>
                </div>
            </div>

            <notification
                notif-title="{{ __('admin::app.notification.notification-title', ['read' => 0]) }}"
                get-notification-url="{{ route('admin.notification.get_notification') }}"
                view-all="{{ route('admin.notification.index') }}"
                order-view-url="{{ \URL::to('/') }}/{{ config('app.admin_url')}}/viewed-notifications/"
                pusher-key="{{ env('PUSHER_APP_KEY') }}"
                pusher-cluster="{{ env('PUSHER_APP_CLUSTER') }}"
                title="{{ __('admin::app.notification.title-plural') }}"
                view-all-title="{{ __('admin::app.notification.view-all') }}"
                get-read-all-url="{{ route('admin.notification.read_all') }}"
                order-status-messages="{{ json_encode($orderStatusMessages) }}"
                read-all-title="{{ __('admin::app.notification.read-all') }}"
                locale-code={{ core()->getCurrentLocale()->code }}>

                <div class="notifications">
                    <div class="dropdown-toggle">
                        <i class="icon notification-icon active" style="margin-left: 0px;"></i>
                    </div>
                </div>

            </notification>

            <div class="profile-info">
                <div class="dropdown-toggle">
                    <div style="display: inline-block; vertical-align: middle;">
                        <div class="profile-info-div">
                            @if (auth()->guard('admin')->user()->image)
                                <div class="profile-info-icon">
                                    <img src="{{ auth()->guard('admin')->user()->image_url }}"/>
                                </div>
                            @else
                                <div class="profile-info-icon">
                                    <span>{{ substr(auth()->guard('admin')->user()->name, 0, 1) }}</span>
                                </div>
                            @endif


                            <div class="profile-info-desc">
                                <span class="name">
                                    {{ auth()->guard('admin')->user()->name }}
                                </span>

                                <span class="role">
                                    {{ auth()->guard('admin')->user()->role['name'] }}
                                </span>
                            </div>
                        </div>
                    </div>
                    <i class="icon arrow-down-icon active"></i>
                </div>

                <div class="dropdown-list bottom-right">
                    <span class="app-version">{{ __('admin::app.layouts.app-version', ['version' => 'v' . core()->version()]) }}</span>

                    <div class="dropdown-container">
                        <label>{{ __('admin::app.layouts.account-title') }}</label>
                        <ul>
                            <li>
                                <a href="{{ route('admin.account.edit') }}">{{ __('admin::app.layouts.my-account') }}</a>
                            </li>
                            <li>
                                <a href="{{ route('admin.session.destroy') }}">{{ __('admin::app.layouts.logout') }}</a>
                            </li>
                            <li v-if="!isMobile()" style="display: flex;justify-content: space-between;">
                                <div style="margin-top:7px">{{ __('admin::app.layouts.mode') }}</div>
                                <dark style="margin-top: -9px;width: 83px;"></dark>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>