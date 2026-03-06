@extends('shop::layouts.master')

@section('main-content')

<div class="fixed top-5 right-5 z-50 space-y-3">

    @if(session()->has('success'))
    <div x-data="{ show: true }" x-show="show"
        class="flex items-center justify-between max-w-sm p-4 text-green-800 bg-green-100 border border-green-300 rounded-lg shadow">

        <span class="text-sm font-medium">
            {{ session('success') }}
        </span>

        <button @click="show = false"
            class="ml-4 text-green-700 hover:text-green-900 font-bold">
            ×
        </button>

    </div>
    @endif

    @if(session()->has('falied'))
    <div x-data="{ show: true }" x-show="show"
        class="flex items-center justify-between max-w-sm p-4 text-red-800 bg-red-100 border border-red-300 rounded-lg shadow">

        <span class="text-sm font-medium">
            {{ session('falied') }}
        </span>

        <button @click="show = false"
            class="ml-4 text-red-700 hover:text-red-900 font-bold">
            ×
        </button>

    </div>
    @endif

</div>

<section class="max-w-7xl mx-auto px-4 py-24 font-oswald text-[#2a1f14]">

  <h2 class="text-3xl uppercase mb-12">Cart</h2>

  <div class="grid grid-cols-12 gap-12">

    <!-- Cart Items List -->
    <div class="col-span-7 flex flex-col space-y-6">

      <!-- Single Cart Item -->
      <div class="flex items-center gap-6 border-b border-gray-200 pb-6">

        <img src="/path/to/eyebrow-color.jpg" alt="Eyebrow Color" class="w-28 h-28 rounded-lg object-cover flex-shrink-0">

        <div class="flex flex-col gap-1">
          <h3 class="text-xl uppercase">Eyebrow Color</h3>
          <p class="text-sm text-gray-600">1 hr with Sami</p>
        </div>

        <div class="ml-auto text-xl font-semibold text-[#c07a3a]">
          AED 60
        </div>
      </div>

      <!-- Repeat Cart Items as needed -->
      <div class="flex items-center gap-6 border-b border-gray-200 pb-6">

        <img src="/path/to/eyebrow-color.jpg" alt="Eyebrow Color" class="w-28 h-28 rounded-lg object-cover flex-shrink-0">

        <div class="flex flex-col gap-1">
          <h3 class="text-xl uppercase">Eyebrow Color</h3>
          <p class="text-sm text-gray-600">1 hr with Sami</p>
        </div>

        <div class="ml-auto text-xl font-semibold text-[#c07a3a]">
          AED 60
        </div>
      </div>

    </div>

    <!-- Order Summary -->
    <div class="col-span-5 bg-gray-50 rounded-lg p-8 shadow-lg h-fit">

      <h3 class="text-xl font-semibold uppercase mb-6 border-b border-gray-300 pb-4">
        Order Summary
      </h3>

      <div class="flex justify-between mb-3 text-lg">
        <span>Subtotal</span>
        <span>AED 450</span>
      </div>

      <div class="flex justify-between mb-6 text-lg text-green-600 font-semibold">
        <span>Discounts</span>
        <span>-AED 60</span>
      </div>

      <div class="flex justify-between text-2xl font-bold border-t border-gray-300 pt-4">
        <span>Total</span>
        <span>AED 390</span>
      </div>

      <button
        class="mt-10 w-full bg-[#c07a3a] hover:bg-[#a8652f] transition-colors text-white uppercase font-semibold py-3 rounded-full"
      >
        Continue
      </button>

    </div>

  </div>
</section>

@endsection