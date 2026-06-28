<x-shop::layouts>
    <x-slot:title>
        {{ trans('marketplace::app.seller.orders.title') }}
    </x-slot>

    <div class="container mx-auto py-8">
        <h1 class="text-2xl font-bold mb-6">{{ trans('marketplace::app.seller.orders.title') }}</h1>

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
                        <th class="px-4 py-3 text-left">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($orders as $mOrder)
                        <tr class="border-t">
                            <td class="px-4 py-3">#{{ $mOrder->order?->increment_id }}</td>
                            <td class="px-4 py-3">{{ number_format($mOrder->base_total, 2) }}</td>
                            <td class="px-4 py-3">{{ number_format($mOrder->commission_amount, 2) }}</td>
                            <td class="px-4 py-3">{{ number_format($mOrder->seller_total, 2) }}</td>
                            <td class="px-4 py-3">{{ $mOrder->commission_status }}</td>
                            <td class="px-4 py-3">{{ $mOrder->created_at->format('d/m/Y') }}</td>
                            <td class="px-4 py-3">
                                <a href="{{ route('marketplace.orders.view', $mOrder->id) }}" class="text-blue-600">View</a>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="px-4 py-6 text-center text-gray-500">No orders yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{ $orders->links() }}
    </div>
</x-shop::layouts>
