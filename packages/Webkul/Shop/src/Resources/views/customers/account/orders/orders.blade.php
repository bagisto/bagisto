
@extends('shop::layouts.master')
@section('page_title')
    {{ __('shop::app.customer.account.order.index.page-title') }}
@endsection

@section('content-wrapper')

<div class="account-content">
    @include('shop::customers.account.partials.sidemenu')

    <div class="account-layout">

        <div class="account-head">
            <span class="account-heading">{{ __('shop::app.customer.account.order.index.title') }}</span>
            <div class="horizontal-rule"></div>
        </div>

        <div class="account-items-list">

            <div class="table">
                <table>
                    <thead>
                        <tr>
                            <th> {{ __('shop::app.customer.account.order.index.order_id') }}</th>
                            <th> {{ __('shop::app.customer.account.order.index.date')  }} </th>
                            <th> {{ __('shop::app.customer.account.order.index.status')}} </th>
                            <th> {{ __('shop::app.customer.account.order.index.item') }} </th>
                            <th> {{ __('shop::app.customer.account.order.index.total') }} </th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($orders as $order)
                        <tr>
                            <td>{{ $order->id }}</td>
                            <td></td>
                            <td>Shipped (2)</td>
                            <td>2</td>
                            <td>$35.00</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

        </div>

    </div>


</div>
@endsection