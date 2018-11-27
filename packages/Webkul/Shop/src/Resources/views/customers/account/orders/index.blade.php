@extends('shop::layouts.master')

@section('page_title')
    {{ __('shop::app.customer.account.order.index.page-title') }}
@endsection

@section('content-wrapper')

    <div class="account-content">
        @include('shop::customers.account.partials.sidemenu')

        <div class="account-layout">

            <div class="account-head mb-10">
                <span class="back-icon"><a href="{{ route('customer.account.index') }}"><i class="icon icon-menu-back"></i></a></span>
                <span class="account-heading">
                    {{ __('shop::app.customer.account.order.index.title') }}
                </span>

                <div class="horizontal-rule"></div>
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
                                        @if($order->status == 'processing')
                                            <span class="badge badge-md badge-success">Processing</span>
                                        @elseif($order->status == 'completed')
                                            <span class="badge badge-md badge-success">Completed</span>
                                        @elseif($order->status == "canceled")
                                            <span class="badge badge-md badge-danger">Canceled</span>
                                        @elseif($order->status == "closed")
                                            <span class="badge badge-md badge-info">Closed</span>
                                        @elseif($order->status == "pending")
                                            <span class="badge badge-md badge-warning">Pending</span>
                                        @endif
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

                @foreach($orders as $order)
                    <table class="responsive-table">
                        <tbody>
                            <tr>
                                <td>
                                    {{ __('shop::app.customer.account.order.index.order_id') }}
                                </td>
                                <td>
                                    <a href="{{ route('customer.orders.view', $order->id) }}">
                                        #{{ $order->id }}
                                    </a>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    {{ __('shop::app.customer.account.order.index.date')  }}
                                </td>
                                <td>{{ core()->formatDate($order->created_at, 'd M Y') }}</td>
                            </tr>
                            <tr>
                                <td>
                                    {{ __('shop::app.customer.account.order.index.total')  }}
                                </td>
                                <td>
                                    {{ core()->formatPrice($order->grand_total, $order->order_currency_code) }}
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    {{ __('shop::app.customer.account.order.index.status')  }}
                                </td>
                                <td>
                                    <span class="order-status {{ $order->status }}">{{ $order->status_label }}</span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                @endforeach

                @if (!$orders->count())
                    <div class="responsive-empty">{{ __('admin::app.common.no-result-found') }}</div>
                @endif

            </div>

        </div>

    </div>

@endsection