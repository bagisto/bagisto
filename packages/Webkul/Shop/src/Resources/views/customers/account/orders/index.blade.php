@extends('shop::layouts.master')

@section('page_title')
    {{ __('shop::app.customer.account.order.index.page-title') }}
@endsection

@section('content-wrapper')

    <div class="account-content">
        @include('shop::customers.account.partials.sidemenu')

        <div class="account-layout">

            <div class="account-head">
                <span class="account-heading">
                    {{ __('shop::app.customer.account.order.index.title') }}
                </span>
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
                        </tbody>
                    </table>
                </div>

            </div>

        </div>

    </div>

@endsection