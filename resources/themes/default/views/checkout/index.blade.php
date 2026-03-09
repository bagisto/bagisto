@extends('shop::layouts.master')

@section('main-content')

<section class="max-w-7xl mx-auto px-4 py-24 font-oswald text-[#2a1f14]">

  <h2 class="text-3xl uppercase mb-12">Checkout</h2>

  <div class="grid grid-cols-12 gap-12">

    <!-- Cart Items List -->
    <div class="col-span-7 flex flex-col space-y-6">

      @forelse($cartItems as $item)
      <div class="flex items-center gap-6 border-b border-gray-200 pb-6">

        <img src="{{ $item->product->base_image_url ?? asset('images/placeholder.png') }}" 
             alt="{{ $item->product->name }}" 
             class="w-28 h-28 rounded-lg object-cover flex-shrink-0">

        <div class="flex flex-col gap-1">
          <h3 class="text-xl uppercase">{{ $item->product->name }}</h3>

          @if($item->professional)
          <p class="text-sm text-gray-600">
              @if(isset($item->additional))
                  {{ json_decode($item->additional)->booking_date ?? '' }} 
                  {{ json_decode($item->additional)->booking_time ?? '' }}
              @endif
              with {{ $item->professional->name }}
          </p>
          @endif

        </div>

<div class="ml-auto text-xl font-semibold text-[#c07a3a]">
    {{ core()->currency($item->product->price * $item->quantity) }}
</div>
      </div>
      @empty
      <div class="text-center text-gray-400 col-span-7 py-10">
          Your cart is empty
      </div>
      @endforelse

    </div>

<!-- Order Summary -->
<div class="col-span-5 bg-gray-50 rounded-lg p-8 shadow-lg h-fit">

  <h3 class="text-xl font-semibold uppercase mb-6 border-b border-gray-300 pb-4">
    Order Summary
  </h3>

  <div class="flex justify-between mb-3 text-lg">
    <span>Subtotal</span>
    <span>{{ core()->currency($subtotal) }}</span>
  </div>

  <div class="flex justify-between mb-6 text-lg text-green-600 font-semibold">
    <span>Discounts</span>
    <span>-{{ core()->currency($discount) }}</span>
  </div>

  <div class="flex justify-between text-2xl font-bold border-t border-gray-300 pt-4">
    <span>Total</span>
    <span>{{ core()->currency($total) }}</span>
  </div>

  <a href="{{ route('shop.checkout.onepage.index') }}">
    <button
      class="mt-10 w-full bg-[#c07a3a] hover:bg-[#a8652f] transition-colors text-white uppercase font-semibold py-3 rounded-full"
    >
      Continue
    </button>
  </a>

</div>

  </div>
</section>

@endsection