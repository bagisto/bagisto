@extends('admin::layouts.content')

@section('page_title')
    {{ __('admin::app.notification.notification-title') }}
@endsection

@php 
    $orderStatus = [
        'all' => trans('admin::app.notification.status.all'),
        'pending' => trans('admin::app.notification.status.pending'),
        'canceled'=> trans('admin::app.notification.status.canceled'),
        'closed' => trans('admin::app.notification.status.closed'),
        'completed'=> trans('admin::app.notification.status.completed'),
        'processing' => trans('admin::app.notification.status.processing') 
    ];

    $orderStatusMessages = [
        'pending' => trans('admin::app.notification.order-status-messages.pending'),
        'canceled'=> trans('admin::app.notification.order-status-messages.canceled'),
        'closed' => trans('admin::app.notification.order-status-messages.closed'),
        'completed'=> trans('admin::app.notification.order-status-messages.completed'),
        'processing' => trans('admin::app.notification.order-status-messages.processing') 
    ];
@endphp

@push('css')
    <style>
        .sr-only{
            display:none;
        }

        .pagination .page-item {          
            height: 40px !important;
            width: 40px !important;
            margin-right: 5px;
            font-size: 16px;
            display: inline-block;
            color: #3a3a3a;
            vertical-align: middle;
            text-decoration: none;
            text-align: center;
        }

        .page-item .pagination-page-nav .active .page-link {
            color:#fff !important;
        }
    </style>
@endpush

@section('content')
    <notification-list  
        url="{{ route('admin.notification.get-notification') }}"
        order-view-url="{{ \URL::to('/') }}/admin/viewed-notifications/"
        pusher-key="{{ env('PUSHER_APP_KEY') }}"
        pusher-cluster="{{ env('PUSHER_APP_CLUSTER') }}"
        title=" {{ __('admin::app.notification.notification-title') }}"
        order-status="{{ json_encode($orderStatus) }}"
        order-status-messages="{{ json_encode($orderStatusMessages) }}"
        no-record-text="{{ __('admin::app.notification.no-record') }}"
        locale-code={{ core()->getCurrentLocale()->code }}>
    </notification-list>
@endsection
