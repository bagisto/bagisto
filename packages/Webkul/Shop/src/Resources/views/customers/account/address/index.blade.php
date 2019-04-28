@extends('shop::layouts.master')

@section('page_title')
    {{ __('shop::app.customer.account.address.index.page-title') }}
@endsection

@section('content-wrapper')

<div class="account-content">

    @include('shop::customers.account.partials.sidemenu')

    <div class="account-layout">

        <div class="account-head">
            <span class="back-icon"><a href="{{ route('customer.account.index') }}"><i class="icon icon-menu-back"></i></a></span>
            <span class="account-heading">{{ __('shop::app.customer.account.address.index.title') }}</span>

            @if (! $addresses->isEmpty())
                <span class="account-action">
                    <a href="{{ route('customer.address.create') }}">{{ __('shop::app.customer.account.address.index.add') }}</a>
                </span>
            @else
                <span></span>
            @endif
            <div class="horizontal-rule"></div>
        </div>

        {!! view_render_event('bagisto.shop.customers.account.address.list.before', ['addresses' => $addresses]) !!}

        <div class="account-table-content">
            @if ($addresses->isEmpty())
                <div>{{ __('shop::app.customer.account.address.index.empty') }}</div>
                <br/>
                <a href="{{ route('customer.address.create') }}">{{ __('shop::app.customer.account.address.index.add') }}</a>
            @else
                <div class="address-holder">
                    @foreach ($addresses as $address)
                        <div class="address-card">
                            <div class="details">
                                <span class="bold">{{ auth()->guard('customer')->user()->name }}</span>
                                <ul class="address-card-list">
                                    <li class="mt-5">
                                        {{ $address->name }}
                                    </li>

                                    <li class="mt-5">
                                        {{ $address->address1 }},
                                    </li>

                                    <li class="mt-5">
                                        {{ $address->city }}
                                    </li>

                                    <li class="mt-5">
                                        {{ $address->state }}
                                    </li>

                                    <li class="mt-5">
                                        {{ country()->name($address->country) }} {{ $address->postcode }}
                                    </li>

                                    <li class="mt-10">
                                        {{ __('shop::app.customer.account.address.index.contact') }} : {{ $address->phone }}
                                    </li>
                                </ul>

                                <div class="control-links mt-20">
                                    <span>
                                        <a href="{{ route('customer.address.edit', $address->id) }}">
                                            {{ __('shop::app.customer.account.address.index.edit') }}
                                        </a>
                                    </span>

                                    <span>
                                        <a href="{{ route('address.delete', $address->id) }}" onclick="deleteAddress('{{ __('shop::app.customer.account.address.index.confirm-delete') }}')">
                                            {{ __('shop::app.customer.account.address.index.delete') }}
                                        </a>
                                    </span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        {!! view_render_event('bagisto.shop.customers.account.address.list.after', ['addresses' => $addresses]) !!}
    </div>
</div>
@endsection

@push('scripts')
    <script>
        function deleteAddress(message) {
            if (!confirm(message))
            event.preventDefault();
        }
    </script>
@endpush
