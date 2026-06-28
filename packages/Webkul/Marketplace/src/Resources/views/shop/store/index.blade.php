<x-shop::layouts>
    <x-slot:title>
        {{ $seller->meta_title ?: $seller->shop_name }}
    </x-slot>

    {{-- ============ STORE HEADER ============ --}}
    <div class="relative">
        @if ($seller->banner)
            <div class="h-48 md:h-64 w-full bg-cover bg-center"
                 style="background-image:url('{{ asset('storage/'.$seller->banner) }}')"></div>
        @else
            <div class="h-40 md:h-56 w-full bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-500"></div>
        @endif

        <div class="container mx-auto px-4">
            <div class="flex items-end gap-4 -mt-12 md:-mt-16">
                <div class="h-24 w-24 md:h-28 md:w-28 rounded-2xl bg-white shadow-lg ring-1 ring-gray-200 flex items-center justify-center overflow-hidden shrink-0">
                    @if ($seller->logo)
                        <img src="{{ asset('storage/'.$seller->logo) }}" alt="{{ $seller->shop_name }}" class="h-full w-full object-cover">
                    @else
                        <span class="text-3xl font-extrabold text-indigo-500">{{ mb_strtoupper(mb_substr($seller->shop_name, 0, 1)) }}</span>
                    @endif
                </div>
                <div class="pb-2">
                    <h1 class="text-2xl md:text-3xl font-bold">{{ $seller->shop_name }}</h1>
                    <p class="text-sm text-gray-500">{{ $products->total() }} produto(s)</p>
                </div>
            </div>
        </div>
    </div>

    <div class="container mx-auto px-4 py-8">
        @if ($seller->description)
            <p class="text-gray-600 max-w-3xl mb-8">{{ $seller->description }}</p>
        @endif

        {{-- ============ PRODUCT GRID ============ --}}
        @if ($products->count())
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                @foreach ($products as $flat)
                    @php
                        $image = optional($flat->product?->images?->first())->path;
                    @endphp
                    <a href="{{ url($flat->url_key) }}"
                       class="group rounded-xl border border-gray-200 overflow-hidden hover:shadow-md transition">
                        <div class="aspect-square bg-gray-50 flex items-center justify-center overflow-hidden">
                            @if ($image)
                                <img src="{{ asset('storage/'.$image) }}" alt="{{ $flat->name }}"
                                     class="h-full w-full object-cover group-hover:scale-105 transition">
                            @else
                                <svg class="h-14 w-14 text-gray-300" fill="currentColor" viewBox="0 0 24 24"><path d="M4 5h16a1 1 0 011 1v12a1 1 0 01-1 1H4a1 1 0 01-1-1V6a1 1 0 011-1zm1 2v8.59l3.3-3.3a1 1 0 011.4 0l2.3 2.3 3.3-3.3a1 1 0 011.4 0L19 14.59V7H5z"/></svg>
                            @endif
                        </div>
                        <div class="p-3">
                            <p class="text-sm font-medium line-clamp-2 group-hover:text-indigo-600">{{ $flat->name }}</p>
                            <p class="mt-1 font-bold">{{ core()->getCurrentCurrency()?->symbol ?? '$' }}{{ number_format((float) $flat->price, 2) }}</p>
                        </div>
                    </a>
                @endforeach
            </div>

            <div class="mt-8">
                {{ $products->links() }}
            </div>
        @else
            <div class="text-center text-gray-500 py-16">
                Esta loja ainda não tem produtos.
            </div>
        @endif
    </div>
</x-shop::layouts>
