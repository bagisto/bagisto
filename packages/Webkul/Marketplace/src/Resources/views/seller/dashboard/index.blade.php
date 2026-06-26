@extends('shop::layouts.master')

@section('page_title')
    {{ trans('marketplace::app.seller.dashboard.title') }}
@stop

@section('content-wrapper')
    <div class="container mx-auto py-8">
        <h1 class="text-2xl font-bold mb-6">{{ trans('marketplace::app.seller.dashboard.title') }}</h1>

        @if (!$seller->isApproved())
            <div class="bg-yellow-100 text-yellow-800 p-4 rounded mb-6">
                Your seller account is pending approval.
            </div>
        @endif

        <div class="grid grid-cols-3 gap-4 mb-8">
            <div class="bg-white rounded shadow p-6 text-center">
                <p class="text-gray-500 text-sm">{{ trans('marketplace::app.seller.dashboard.pending-earnings') }}</p>
                <p class="text-2xl font-bold mt-2">{{ config('marketplace.default_currency') }} {{ number_format($pendingEarnings, 2) }}</p>
            </div>
            <div class="bg-white rounded shadow p-6 text-center">
                <p class="text-gray-500 text-sm">{{ trans('marketplace::app.seller.dashboard.total-earnings') }}</p>
                <p class="text-2xl font-bold mt-2">{{ config('marketplace.default_currency') }} {{ number_format($totalEarnings, 2) }}</p>
            </div>
            <div class="bg-white rounded shadow p-6 text-center">
                <p class="text-gray-500 text-sm">Subscription</p>
                <p class="text-xl font-bold mt-2">{{ $seller->subscription?->plan?->name ?? 'Free' }}</p>
            </div>
        </div>

        <h2 class="text-xl font-semibold mb-4">{{ trans('marketplace::app.seller.dashboard.recent-orders') }}</h2>
        <div class="bg-white rounded shadow overflow-hidden">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left">Order #</th>
                        <th class="px-4 py-3 text-left">Total</th>
                        <th class="px-4 py-3 text-left">Commission</th>
                        <th class="px-4 py-3 text-left">You Receive</th>
                        <th class="px-4 py-3 text-left">Status</th>
                        <th class="px-4 py-3 text-left">Date</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($recentOrders as $mOrder)
                        <tr class="border-t">
                            <td class="px-4 py-3">#{{ $mOrder->order?->increment_id }}</td>
                            <td class="px-4 py-3">{{ number_format($mOrder->base_total, 2) }}</td>
                            <td class="px-4 py-3">{{ number_format($mOrder->commission_amount, 2) }}</td>
                            <td class="px-4 py-3">{{ number_format($mOrder->seller_total, 2) }}</td>
                            <td class="px-4 py-3">{{ $mOrder->commission_status }}</td>
                            <td class="px-4 py-3">{{ $mOrder->created_at->format('d/m/Y') }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="px-4 py-6 text-center text-gray-500">No orders yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-6 flex gap-4">
            <a href="{{ route('marketplace.products.index') }}" class="bg-blue-600 text-white px-4 py-2 rounded">My Products</a>
            <a href="{{ route('marketplace.orders.index') }}" class="bg-gray-600 text-white px-4 py-2 rounded">My Orders</a>
            <a href="{{ route('marketplace.payouts.index') }}" class="bg-green-600 text-white px-4 py-2 rounded">Payouts</a>
        </div>
    </div>
@stop
