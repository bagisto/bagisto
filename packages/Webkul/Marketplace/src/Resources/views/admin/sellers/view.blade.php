@extends('admin::layouts.master')

@section('page_title')
    {{ $seller->shop_name }}
@stop

@section('content-wrapper')
    <div class="content full-page">
        <div class="page-header">
            <div class="page-title">
                <h1>{{ $seller->shop_name }}</h1>
            </div>
            <div class="page-action">
                @if (!$seller->isApproved())
                    <form method="POST" action="{{ route('admin.marketplace.sellers.approve', $seller->id) }}" style="display:inline">
                        @csrf
                        <button type="submit" class="btn btn-success">Approve</button>
                    </form>
                @else
                    <form method="POST" action="{{ route('admin.marketplace.sellers.suspend', $seller->id) }}" style="display:inline">
                        @csrf
                        <button type="submit" class="btn btn-danger">Suspend</button>
                    </form>
                @endif
            </div>
        </div>

        <div class="page-content">
            <div class="form-container">
                <table class="table">
                    <tr><th>Status</th><td><span class="badge badge-{{ $seller->status->color() }}">{{ $seller->status->label() }}</span></td></tr>
                    <tr><th>Customer</th><td>{{ $seller->customer?->name }} ({{ $seller->customer?->email }})</td></tr>
                    <tr><th>Shop URL</th><td>{{ $seller->shop_url }}</td></tr>
                    <tr><th>Phone</th><td>{{ $seller->phone ?? '—' }}</td></tr>
                    <tr><th>Commission Rate</th><td>{{ $seller->commission_rate }}%</td></tr>
                    <tr><th>Subscription</th><td>{{ $seller->subscription?->plan?->name ?? 'None' }}</td></tr>
                    <tr><th>Registered</th><td>{{ $seller->created_at->format('d/m/Y H:i') }}</td></tr>
                </table>

                @if ($seller->description)
                    <p>{{ $seller->description }}</p>
                @endif
            </div>
        </div>
    </div>
@stop
