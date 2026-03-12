@extends('shop::layouts.master')

@section('main-content')

<section class="max-w-2xl mx-auto px-4 py-20 text-center">

    @if(isset($orderId))
        <div class="bg-green-100 border border-green-400 text-green-700 px-6 py-8 rounded-lg shadow-lg">
            <h2 class="text-2xl font-bold mb-4">Thank you for your order!</h2>
            <p class="mb-2">Your order has been placed successfully.</p>
            <p class="font-semibold">Order ID: <span class="text-[#c07a3a]">{{ $orderId }}</span></p>
        </div>

        <a href="{{ route('sbt.perfume.index') }}" class="mt-8 inline-block bg-[#c07a3a] text-white px-6 py-3 rounded-full uppercase font-semibold">
            Continue Shopping
        </a>
    @else
        <div class="bg-red-100 border border-red-400 text-red-700 px-6 py-8 rounded-lg shadow-lg">
            <h2 class="text-2xl font-bold mb-4">Oops!</h2>
            <p>No recent order found.</p>
        </div>
    @endif

</section>

@endsection