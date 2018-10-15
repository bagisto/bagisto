@extends('shop::layouts.master')

@section('page_title')
    {{ __('shop::app.customer.account.address.index.page-title') }}
@endsection

@section('content-wrapper')

<div class="account-content">

    @include('shop::customers.account.partials.sidemenu')

    <div class="account-layout">

        <div class="account-head">
            <span class="account-heading">{{ __('shop::app.customer.account.address.index.title') }}</span>

            @if(!$address->isEmpty())
            <span class="account-edit">
                <a href="{{ route('customer.address.edit') }}">
                    {{ __('shop::app.customer.account.address.index.edit') }}
                </a>
            </span>
            @endif
            <div class="horizontal-rule"></div>
        </div>

        <div class="account-table-content">
            @if($address->isEmpty())
                <div>{{ __('shop::app.customer.account.address.index.empty') }}</div>
                <br/>
                <a href="{{ route('customer.address.create') }}">{{ __('shop::app.customer.account.address.index.add') }}</a>
            @else
                <table>
                    <tbody>
                        <tr>
                            <td>{{ __('shop::app.customer.account.address.create.address1') }}</td>
                            <td>{{ $address['address1'] }}</td>
                        </tr>

                        <tr>
                            <td>{{ __('shop::app.customer.account.address.create.address2') }}</td>
                            <td>{{ $address['address2'] }}</td>
                        </tr>

                        <tr>
                            <td>{{ __('shop::app.customer.account.address.create.country') }}</td>
                            <td>{{ $address['country'] }}</td>
                        </tr>

                        <tr>
                            <td>{{ __('shop::app.customer.account.address.create.state') }}</td>
                            <td>{{ $address['state'] }}</td>
                        </tr>

                        <tr>
                            <td>{{ __('shop::app.customer.account.address.create.city') }}</td>
                            <td>{{ $address['city'] }}</td>
                        </tr>

                        <tr>
                            <td>{{ __('shop::app.customer.account.address.create.postcode') }}</td>
                            <td>{{ $address['postcode'] }}</td>
                        </tr>
                    </tbody>
                </table>
            @endif

        </div>

    </div>

</div>
@endsection
