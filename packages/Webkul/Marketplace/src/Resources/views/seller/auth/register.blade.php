@extends('shop::layouts.master')

@section('page_title')
    {{ trans('marketplace::app.seller.auth.register') }}
@stop

@section('content-wrapper')
    <div class="container mx-auto max-w-lg py-8">
        <h1 class="text-2xl font-bold mb-6">{{ trans('marketplace::app.seller.auth.register') }}</h1>

        @if (session('error'))
            <div class="bg-red-100 text-red-700 p-4 rounded mb-4">{{ session('error') }}</div>
        @endif

        <form method="POST" action="{{ route('marketplace.register.store') }}">
            @csrf
            <div class="mb-4">
                <label class="block font-medium mb-1">Shop Name *</label>
                <input type="text" name="shop_name" value="{{ old('shop_name') }}"
                       class="w-full border rounded px-3 py-2 @error('shop_name') border-red-500 @enderror" required>
                @error('shop_name')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
            </div>

            <div class="mb-4">
                <label class="block font-medium mb-1">Shop URL (slug) *</label>
                <input type="text" name="shop_url" value="{{ old('shop_url') }}"
                       class="w-full border rounded px-3 py-2 @error('shop_url') border-red-500 @enderror" required>
                <p class="text-gray-500 text-sm mt-1">Only letters, numbers, hyphens and underscores.</p>
                @error('shop_url')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
            </div>

            <div class="mb-4">
                <label class="block font-medium mb-1">Phone</label>
                <input type="text" name="phone" value="{{ old('phone') }}"
                       class="w-full border rounded px-3 py-2">
            </div>

            <div class="mb-6">
                <label class="block font-medium mb-1">Description</label>
                <textarea name="description" rows="4"
                          class="w-full border rounded px-3 py-2">{{ old('description') }}</textarea>
            </div>

            <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700">
                Register as Seller
            </button>
        </form>
    </div>
@stop
