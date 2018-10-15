
@extends('shop::layouts.master')
@section('page_title')
    {{ __('shop::app.customer.account.order.index.page-title') }}
@endsection

@section('content-wrapper')

<div class="account-content">

    @include('shop::customers.account.partials.sidemenu')

    <div class="account profile">

        <div class="section-head">
            <h3> {{ __('shop::app.customer.account.order.index.title') }} </h3>
        </div>

        {{ dd($order)}}

        <div class="profile-content">
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
                        <tr>
                            <td>PROD124</td>
                            <td>Apple iPhone 7- White-32GB</td>
                            <td>Packed (2)</td>
                            <td>2</td>
                            <td>$700.00</td>
                        </tr>
                        <tr>
                            <td>PROD128</td>
                            <td>Blue Linen T-Shirt for Men- Small- Red</td>
                            <td>Shipped (2)</td>
                            <td>2</td>
                            <td>$35.00</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

    </div>

</div>
@endsection