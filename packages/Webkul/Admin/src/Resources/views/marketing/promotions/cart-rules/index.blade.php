@extends('admin::layouts.content')

@section('page_title')
    {{ __('admin::app.promotions.cart-rules.title') }}
@stop

@section('content')
    <div class="content">
        @php
            $customer_group = core()->getRequestedCustomerGroupCode();

            $channel = core()->getRequestedChannelCode(false);
        @endphp

        <div class="page-header">
            <div class="page-title">
                <h1>{{ __('admin::app.promotions.cart-rules.title') }}</h1>
            </div>

            <div class="page-action">
                @if (bouncer()->hasPermission('marketing.promotions.cart-rules.create'))
                    <a href="{{ route('admin.cart_rules.create') }}" class="btn btn-lg btn-primary">
                        {{ __('admin::app.promotions.cart-rules.add-title') }}
                    </a>
                @endif
            </div>
        </div>

        <div class="page-content">
            <datagrid-plus src="{{ route('admin.cart_rules.index') }}"></datagrid-plus>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function reloadPage(getVar, getVal) {
            let url = new URL(window.location.href);
            
            url.searchParams.set(getVar, getVal);

            window.location.href = url.href;
        }
    </script>
@endpush
