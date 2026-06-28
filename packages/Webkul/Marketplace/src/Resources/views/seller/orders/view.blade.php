<x-shop::layouts>
    <x-slot:title>
        Order #{{ $order->order?->increment_id }}
    </x-slot>

    <div class="container mx-auto py-8">
        <h1 class="text-2xl font-bold mb-6">Order #{{ $order->order?->increment_id }}</h1>

        <div class="bg-white rounded shadow p-6">
            <table class="w-full">
                <tr><th class="text-left py-2 w-1/3">Order Date</th><td>{{ $order->order?->created_at?->format('d/m/Y H:i') }}</td></tr>
                <tr><th class="text-left py-2">Base Total</th><td>{{ number_format($order->base_total, 2) }}</td></tr>
                <tr><th class="text-left py-2">Commission Rate</th><td>{{ $order->commission_rate }}%</td></tr>
                <tr><th class="text-left py-2">Commission Amount</th><td>{{ number_format($order->commission_amount, 2) }}</td></tr>
                <tr><th class="text-left py-2">You Receive</th><td class="font-bold text-green-600">{{ number_format($order->seller_total, 2) }}</td></tr>
                <tr><th class="text-left py-2">Payment Status</th><td>{{ $order->commission_status }}</td></tr>
                @if ($order->paid_at)
                    <tr><th class="text-left py-2">Paid At</th><td>{{ $order->paid_at->format('d/m/Y') }}</td></tr>
                @endif
            </table>
        </div>

        <a href="{{ route('marketplace.orders.index') }}" class="mt-4 inline-block text-blue-600">← Back to Orders</a>
    </div>
</x-shop::layouts>
