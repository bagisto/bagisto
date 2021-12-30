@extends('admin::layouts.content')

@section('page_title')
    {{ __('admin::app.notification.title') }}
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

        .page-item .pagination-page-nav .active .page-link{
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
        title=" {{ __('admin::app.notification.title') }}"
        order-status="{{ json_encode($orderStatus) }}"
        no-record-text="{{ __('admin::app.notification.no-record') }}">
    </notification-list>
@endsection
