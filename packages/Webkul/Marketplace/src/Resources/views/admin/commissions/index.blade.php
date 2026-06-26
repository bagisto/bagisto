@extends('admin::layouts.master')

@section('page_title')
    {{ trans('marketplace::app.admin.commissions.index.title') }}
@stop

@section('content-wrapper')
    <div class="content full-page">
        <div class="page-header">
            <div class="page-title">
                <h1>{{ trans('marketplace::app.admin.commissions.index.title') }}</h1>
            </div>
        </div>

        <div class="page-content">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Order</th>
                            <th>Seller</th>
                            <th>Base Total</th>
                            <th>Commission</th>
                            <th>Seller Receives</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($commissions as $commission)
                            <tr>
                                <td>{{ $commission->id }}</td>
                                <td>#{{ $commission->order?->increment_id }}</td>
                                <td>{{ $commission->seller?->shop_name }}</td>
                                <td>{{ number_format($commission->base_total, 2) }}</td>
                                <td>{{ number_format($commission->commission_amount, 2) }} ({{ $commission->commission_rate }}%)</td>
                                <td>{{ number_format($commission->seller_total, 2) }}</td>
                                <td>{{ $commission->commission_status }}</td>
                                <td>
                                    @if ($commission->commission_status === 'pending')
                                        <form method="POST" action="{{ route('admin.marketplace.commissions.pay', $commission->id) }}">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-success">Mark Paid</button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{ $commissions->links() }}
        </div>
    </div>
@stop
