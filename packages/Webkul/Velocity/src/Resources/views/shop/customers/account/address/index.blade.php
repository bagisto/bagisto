@extends('shop::customers.account.index')

@section('page_title')
    {{ __('shop::app.customer.account.address.index.page-title') }}
@endsection

@section('page-detail-wrapper')
    @if ($addresses->isEmpty())
        <a href="{{ route('customer.address.create') }}" class="theme-btn light unset address-button">
            {{ __('shop::app.customer.account.address.index.add') }}
        </a>
    @endif

    <div class="account-head mt-3">
        <span class="account-heading">
            {{ __('shop::app.customer.account.address.index.title') }}
        </span>

        @if (! $addresses->isEmpty())
            <span class="account-action">
                <a href="{{ route('customer.address.create') }}" class="theme-btn light unset float-right">
                    {{ __('shop::app.customer.account.address.index.add') }}
                </a>
            </span>
        @endif
    </div>

    {!! view_render_event('bagisto.shop.customers.account.address.list.before', ['addresses' => $addresses]) !!}

        <div class="account-table-content">
            @if ($addresses->isEmpty())
                <div>{{ __('shop::app.customer.account.address.index.empty') }}</div>
            @else
                <div class="row address-holder no-padding">
                    @foreach ($addresses as $address)
                        <div class="col-lg-4 col-md-6 col-xs-12">
                            <div class="card m-1">
                                <div class="card-body">
                                    <h5 class="card-title fw6">{{ $address->first_name }} {{ $address->last_name }}</h5>

                                    <ul type="none">
                                        <li>{{ $address->address1 }}</li>
                                        <li>{{ $address->city }}</li>
                                        <li>{{ $address->state }}</li>
                                        <li>{{ core()->country_name($address->country) }} {{ $address->postcode }}</li>
                                        <li>
                                            {{ __('shop::app.customer.account.address.index.contact') }} : {{ $address->phone }}
                                        </li>
                                    </ul>

                                    <a class="card-link" href="{{ route('customer.address.edit', $address->id) }}">
                                        {{ __('shop::app.customer.account.address.index.edit') }}
                                    </a>

                                    <a class="card-link" href="javascript:void(0);" onclick="deleteAddress('{{ __('shop::app.customer.account.address.index.confirm-delete') }}', '{{ $address->id }}')">
                                        {{ __('shop::app.customer.account.address.index.delete') }}
                                    </a>

                                    <form id="deleteAddressForm{{ $address->id }}" action="{{ route('address.delete', $address->id) }}" method="post">
                                        @method('delete')

                                        @csrf
                                    </form>
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
        function deleteAddress(message, addressId) {
            if (! confirm(message)) {
                return;
            }

            $(`#deleteAddressForm${addressId}`).submit();
        }
    </script>
@endpush

@if ($addresses->isEmpty())
    <style>
        a#add-address-button {
            position: absolute;
            margin-top: 92px;
        }

        .address-button {
            position: absolute;
            z-index: 1 !important;
            margin-top: 110px !important;
        }
    </style>
@endif
