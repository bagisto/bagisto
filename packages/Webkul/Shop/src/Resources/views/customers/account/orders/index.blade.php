@extends('shop::layouts.master')

@section('page_title')
    {{ __('shop::app.customer.account.order.index.page-title') }}
@endsection

@section('content-wrapper')

    <div class="account-content">
        @include('shop::customers.account.partials.sidemenu')

        <div class="account-layout">

            <div class="account-head">
                <span class="back-icon"><a href="{{ route('customer.account.index') }}"><i class="icon icon-menu-back"></i></a></span>
                <span class="account-heading">
                    {{ __('shop::app.customer.account.order.index.title') }}
                </span>
                <span></span>
            </div>

            <div class="account-items-list">

                <div class="table">
                    <table>
                        <thead>
                            <tr>
                                <th> {{ __('shop::app.customer.account.order.index.order_id') }}</th>
                                <th> {{ __('shop::app.customer.account.order.index.date')  }} </th>
                                <th> {{ __('shop::app.customer.account.order.index.total') }} </th>
                                <th> {{ __('shop::app.customer.account.order.index.status')}} </th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach($orders as $order)

                                <tr>
                                    <td>
                                        <a href="{{ route('customer.orders.view', $order->id) }}">
                                            #{{ $order->id }}
                                        </a>
                                    </td>

                                    <td>{{ core()->formatDate($order->created_at, 'd M Y') }}</td>

                                    <td>
                                        {{ core()->formatPrice($order->grand_total, $order->order_currency_code) }}
                                    </td>

                                    <td>
                                        <span class="order-status {{ $order->status }}">{{ $order->status_label }}</span>
                                    </td>
                                </tr>

                            @endforeach

                            @if (!$orders->count())
                                <tr>
                                    <td class="empty" colspan="4">{{ __('admin::app.common.no-result-found') }}</td>
                                <tr>
                            @endif
                        </tbody>
                    </table>
                </div>

            </div>

        </div>

    </div>

@endsection