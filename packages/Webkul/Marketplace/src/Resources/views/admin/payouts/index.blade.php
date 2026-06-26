@extends('admin::layouts.master')

@section('page_title')
    {{ trans('marketplace::app.admin.payouts.index.title') }}
@stop

@section('content-wrapper')
    <div class="content full-page">
        <div class="page-header">
            <div class="page-title">
                <h1>{{ trans('marketplace::app.admin.payouts.index.title') }}</h1>
            </div>
        </div>

        <div class="page-content">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Seller</th>
                            <th>Amount</th>
                            <th>Method</th>
                            <th>Status</th>
                            <th>Requested</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($payouts as $payout)
                            <tr>
                                <td>{{ $payout->id }}</td>
                                <td>{{ $payout->seller?->shop_name }}</td>
                                <td>{{ $payout->currency }} {{ number_format($payout->amount, 2) }}</td>
                                <td>{{ $payout->payment_method }}</td>
                                <td>{{ $payout->status->value }}</td>
                                <td>{{ $payout->created_at->format('d/m/Y') }}</td>
                                <td>
                                    @if ($payout->status->value === 'requested')
                                        <form method="POST" action="{{ route('admin.marketplace.payouts.approve', $payout->id) }}" style="display:inline">
                                            @csrf
                                            <input type="hidden" name="transaction_id" value="">
                                            <button type="submit" class="btn btn-sm btn-success">Approve</button>
                                        </form>
                                        <form method="POST" action="{{ route('admin.marketplace.payouts.reject', $payout->id) }}" style="display:inline">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-danger">Reject</button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{ $payouts->links() }}
        </div>
    </div>
@stop
