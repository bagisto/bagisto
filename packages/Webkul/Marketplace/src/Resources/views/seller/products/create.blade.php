@extends('shop::layouts.master')

@section('page_title')
    List a Product
@stop

@section('content-wrapper')
    <div class="container mx-auto max-w-lg py-8">
        <h1 class="text-2xl font-bold mb-6">List a Product</h1>

        <form method="POST" action="{{ route('marketplace.products.store') }}">
            @csrf
            <div class="mb-4">
                <label class="block font-medium mb-1">Product ID *</label>
                <input type="number" name="product_id" value="{{ old('product_id') }}"
                       class="w-full border rounded px-3 py-2 @error('product_id') border-red-500 @enderror" required>
                <p class="text-gray-500 text-sm mt-1">Enter the product ID from the catalog.</p>
                @error('product_id')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
            </div>

            <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700">
                Submit for Approval
            </button>
            <a href="{{ route('marketplace.products.index') }}" class="ml-3 text-gray-600">Cancel</a>
        </form>
    </div>
@stop
