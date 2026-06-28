<x-shop::layouts>
    <x-slot:title>
        {{ trans('marketplace::app.seller.products.title') }}
    </x-slot>

    <div class="container mx-auto py-8">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold">{{ trans('marketplace::app.seller.products.title') }}</h1>
            <a href="{{ route('marketplace.products.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded">
                {{ trans('marketplace::app.seller.products.add-btn') }}
            </a>
        </div>

        <div class="bg-white rounded shadow overflow-hidden">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left">Product</th>
                        <th class="px-4 py-3 text-left">SKU</th>
                        <th class="px-4 py-3 text-left">Status</th>
                        <th class="px-4 py-3 text-left">Listed</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($products as $sp)
                        <tr class="border-t">
                            <td class="px-4 py-3">{{ $sp->product?->name }}</td>
                            <td class="px-4 py-3">{{ $sp->product?->sku }}</td>
                            <td class="px-4 py-3">{{ $sp->status }}</td>
                            <td class="px-4 py-3">{{ $sp->created_at->format('d/m/Y') }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="px-4 py-6 text-center text-gray-500">No products listed yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{ $products->links() }}
    </div>
</x-shop::layouts>
