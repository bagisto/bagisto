@extends('admin::layouts.master')

@section('page_title')
    {{ trans('marketplace::app.admin.sellers.index.title') }}
@stop

@section('content-wrapper')
    <div class="content full-page">
        <div class="page-header">
            <div class="page-title">
                <h1>{{ trans('marketplace::app.admin.sellers.index.title') }}</h1>
            </div>
        </div>

        <div class="page-content">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Shop Name</th>
                            <th>Customer</th>
                            <th>Status</th>
                            <th>Commission</th>
                            <th>Created</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($sellers as $seller)
                            <tr>
                                <td>{{ $seller->id }}</td>
                                <td>{{ $seller->shop_name }}</td>
                                <td>{{ $seller->customer?->name ?? '—' }}</td>
                                <td>
                                    <span class="badge badge-{{ $seller->status->color() }}">
                                        {{ $seller->status->label() }}
                                    </span>
                                </td>
                                <td>{{ $seller->commission_rate }}%</td>
                                <td>{{ $seller->created_at->format('d/m/Y') }}</td>
                                <td>
                                    <a href="{{ route('admin.marketplace.sellers.view', $seller->id) }}"
                                       class="btn btn-sm btn-secondary">View</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{ $sellers->links() }}
        </div>
    </div>
@stop
