@extends('shop::customers.account.index')

@section('page_title')
    {{ __('shop::app.customer.account.review.index.page-title') }}
@endsection

@section('page-detail-wrapper')
    <div class="account-head">
        <span class="back-icon"><a href="{{ route('customer.account.index') }}"><i class="icon icon-menu-back"></i></a></span>
        <span class="account-heading">{{ __('shop::app.customer.account.address.index.title') }}</span>

        @if (! $addresses->isEmpty())
            <span class="account-action">
                <a href="{{ route('customer.address.create') }}" class="theme-btn light unset pull-right">
                    {{ __('shop::app.customer.account.address.index.add') }}
                </a>
            </span>
        @endif

        <div class="horizontal-rule"></div>
    </div>

    {!! view_render_event('bagisto.shop.customers.account.address.list.before', ['addresses' => $addresses]) !!}

        <div class="account-table-content">
            @if ($addresses->isEmpty())
                <div>{{ __('shop::app.customer.account.address.index.empty') }}</div>
                <a href="{{ route('customer.address.create') }}" class="theme-btn light unset pull-right">
                    {{ __('shop::app.customer.account.address.index.add') }}
                </a>
            @else
                <div class="address-holder row col-12">
                    @foreach ($addresses as $address)
                        <div class="col-4">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title fw6">{{ auth()->guard('customer')->user()->name }} {{ $address->name }}</h5>

                                    <ul type="none">
                                        {{-- <li>{{ $address->name }}</li> --}}
                                        <li>{{ $address->address1 }},</li>
                                        <li>{{ $address->city }},</li>
                                        <li>{{ $address->state }},</li>
                                        <li>{{ core()->country_name($address->country) }} {{ $address->postcode }}</li>
                                        <li>
                                            {{ __('shop::app.customer.account.address.index.contact') }} : {{$address->phone }}
                                        </li>
                                    </ul>

                                    <a class="card-link" href="{{ route('customer.address.edit', $address->id) }}">
                                        {{ __('shop::app.customer.account.address.index.edit') }}
                                    </a>

                                    <a
                                        class="card-link"
                                        href="{{ route('address.delete', $address->id) }}"
                                        onclick="deleteAddress('{{ __('shop::app.customer.account.address.index.confirm-delete') }}')">

                                        {{ __('shop::app.customer.account.address.index.delete') }}
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

    {!! view_render_event('bagisto.shop.customers.account.address.list.after', ['addresses' => $addresses]) !!}
@endsection

@push('scripts')
    <script>
        function deleteAddress(message) {
            if (!confirm(message))
            event.preventDefault();
        }
    </script>
@endpush
