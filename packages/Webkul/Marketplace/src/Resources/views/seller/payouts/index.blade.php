@extends('shop::layouts.master')

@section('page_title')
    {{ trans('marketplace::app.seller.payouts.title') }}
@stop

@section('content-wrapper')
    <div class="container mx-auto py-8">
        <h1 class="text-2xl font-bold mb-6">{{ trans('marketplace::app.seller.payouts.title') }}</h1>

        <div class="bg-blue-50 rounded p-4 mb-6 flex justify-between items-center">
            <div>
                <p class="text-sm text-gray-500">{{ trans('marketplace::app.seller.payouts.available-balance') }}</p>
                <p class="text-3xl font-bold">{{ config('marketplace.default_currency') }} {{ number_format($availableBalance, 2) }}</p>
            </div>
            <button onclick="document.getElementById('payout-modal').classList.remove('hidden')"
                    class="bg-green-600 text-white px-4 py-2 rounded">
                {{ trans('marketplace::app.seller.payouts.request-btn') }}
            </button>
        </div>

        <div id="payout-modal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-white rounded shadow-lg p-6 w-full max-w-md">
                <h2 class="text-xl font-bold mb-4">Request Payout</h2>
                <form method="POST" action="{{ route('marketplace.payouts.request') }}">
                    @csrf
                    <div class="mb-4">
                        <label class="block font-medium mb-1">Amount (min. {{ config('marketplace.default_currency') }} {{ config('marketplace.min_payout_amount') }}) *</label>
                        <input type="number" name="amount" min="{{ config('marketplace.min_payout_amount') }}" step="0.01"
                               class="w-full border rounded px-3 py-2" required>
                    </div>
                    <div class="mb-4">
                        <label class="block font-medium mb-1">Payment Method *</label>
                        <select name="payment_method" class="w-full border rounded px-3 py-2" required>
                            <option value="pix">PIX</option>
                            <option value="bank_transfer">Bank Transfer</option>
                            <option value="paypal">PayPal</option>
                        </select>
                    </div>
                    <div class="mb-4">
                        <label class="block font-medium mb-1">Payment Key / Details *</label>
                        <input type="text" name="payment_details[key]" class="w-full border rounded px-3 py-2" required
                               placeholder="PIX key, IBAN, or PayPal email">
                    </div>
                    <div class="flex gap-3">
                        <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded">Submit Request</button>
                        <button type="button" onclick="document.getElementById('payout-modal').classList.add('hidden')"
                                class="bg-gray-200 text-gray-700 px-4 py-2 rounded">Cancel</button>
                    </div>
                </form>
            </div>
        </div>

        <div class="bg-white rounded shadow overflow-hidden">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left">Amount</th>
                        <th class="px-4 py-3 text-left">Method</th>
                        <th class="px-4 py-3 text-left">Status</th>
                        <th class="px-4 py-3 text-left">Requested</th>
                        <th class="px-4 py-3 text-left">Paid</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($payouts as $payout)
                        <tr class="border-t">
                            <td class="px-4 py-3">{{ $payout->currency }} {{ number_format($payout->amount, 2) }}</td>
                            <td class="px-4 py-3">{{ $payout->payment_method }}</td>
                            <td class="px-4 py-3">{{ $payout->status->value }}</td>
                            <td class="px-4 py-3">{{ $payout->created_at->format('d/m/Y') }}</td>
                            <td class="px-4 py-3">{{ $payout->paid_at?->format('d/m/Y') ?? '—' }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="px-4 py-6 text-center text-gray-500">No payout requests yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{ $payouts->links() }}
    </div>
@stop
