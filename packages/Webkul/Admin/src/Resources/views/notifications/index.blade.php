@php 
    $orderStatus = [
        'all'        => trans('admin::app.notification.status.all'),
        'pending'    => trans('admin::app.notification.status.pending'),
        'canceled'   => trans('admin::app.notification.status.canceled'),
        'closed'     => trans('admin::app.notification.status.closed'),
        'completed'  => trans('admin::app.notification.status.completed'),
        'processing' => trans('admin::app.notification.status.processing') 
    ];

    $orderStatusMessages = [
        'pending'    => trans('admin::app.notification.order-status-messages.pending'),
        'canceled'   => trans('admin::app.notification.order-status-messages.canceled'),
        'closed'     => trans('admin::app.notification.order-status-messages.closed'),
        'completed'  => trans('admin::app.notification.order-status-messages.completed'),
        'processing' => trans('admin::app.notification.order-status-messages.processing') 
    ];
@endphp

<x-admin::layouts>
    <x-slot:title>
        @lang('admin::app.notification.notification-title')
    </x-slot:title>

    <x-admin::notification.list
        :url="route('admin.notification.get_notification')"
        :order-view-url="\URL::to('/') . '/' . config('app.admin_url') . '/viewed-notifications/'"
        :pusher-key="env('PUSHER_APP_KEY')"
        :pusher-cluster="env('PUSHER_APP_CLUSTER')"
        :title="trans('admin::app.notification.notification-title')"
        :order-status="json_encode($orderStatus)"
        :order-status-messages="json_encode($orderStatusMessages)"
        :no-record-text="trans('admin::app.notification.no-record')"
        :locale-code="core()->getCurrentLocale()->code"
    >
    </x-admin::notification.list>
</x-admin::layouts>