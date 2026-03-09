@extends('shop::layouts.master')

@section('main-content')

<section class="max-w-7xl mx-auto px-4 py-20">

    <form id="checkout-form" method="POST" action="{{ route('shop.checkout.onepage.orders.store') }}">
        @csrf

        <div class="grid grid-cols-12 gap-12">

            <!-- LEFT SIDE: Date/Time, Address, Payment -->
            <div class="col-span-7 space-y-14">

                <!-- DATE & TIME -->
                <div>
                    <h2 class="text-2xl font-semibold uppercase mb-6 text-[#c07a3a]">Select Date & Time</h2>
                    <div class="bg-[#f3efee] rounded-xl p-8 text-black">
                        <div class="flex items-center justify-between mb-6">
                            <select name="additional[booking_month]" class="border rounded px-4 py-2 text-sm">
                                <option value="2026-03">March 2026</option>
                            </select>
                            <div class="flex gap-3">
                                <button type="button" class="w-10 h-10 rounded-full border flex items-center justify-center">←</button>
                                <button type="button" class="w-10 h-10 rounded-full border border-[#c07a3a] text-[#c07a3a] flex items-center justify-center">→</button>
                            </div>
                        </div>

                        <!-- Days -->
                        <div class="grid grid-cols-6 gap-3 mb-6">
                            @for($i = 11; $i <= 16; $i++)
                                <div class="border rounded-lg p-3 text-center">
                                    <p class="text-sm">{{ \Carbon\Carbon::create(2026, 3, $i)->format('D') }}</p>
                                    <p class="text-lg font-semibold">{{ $i }}</p>
                                    <p class="text-xs text-gray-500">7 Slots</p>
                                </div>
                            @endfor
                        </div>

                        <!-- Time slots -->
                        <div class="grid grid-cols-5 gap-3">
                            @foreach(['7:30 AM','8:30 AM','9:30 AM','10:30 AM','11:30 AM'] as $time)
                                <button type="button" class="border rounded-lg py-2 text-sm" onclick="document.getElementById('booking_time').value='{{ $time }}'">
                                    {{ $time }}
                                </button>
                            @endforeach
                        </div>

                        <input type="hidden" name="additional[booking_date]" id="booking_date" value="">
                        <input type="hidden" name="additional[booking_time]" id="booking_time" value="">
                        <input type="hidden" name="additional[professional_id]" value="{{ $professional->id ?? '' }}">
                    </div>
                </div>

                <!-- ADDRESS -->
                <div>
                    <h2 class="text-2xl font-semibold uppercase mb-6 text-[#c07a3a]">Address</h2>
                    <div class="grid grid-cols-2 gap-6">
                        <input type="text" name="billing[first_name]" placeholder="Enter first name" class="p-3 rounded bg-[#f3efee] text-black" required>
                        <input type="text" name="billing[last_name]" placeholder="Enter last name" class="p-3 rounded bg-[#f3efee] text-black" required>
                        <input type="email" name="billing[email]" placeholder="email@example.com" class="p-3 rounded bg-[#f3efee] text-black" required>
                        <input type="text" name="billing[phone]" placeholder="Enter telephone" class="p-3 rounded bg-[#f3efee] text-black" required>
                        <input type="text" name="billing[address1]" placeholder="Enter street address" class="p-3 rounded bg-[#f3efee] text-black col-span-2" required>
                        <select name="billing[country]" class="p-3 rounded bg-[#f3efee] text-black" required>
                            <option value="AE">United Arab Emirates</option>
                        </select>
                        <input type="text" name="billing[state]" placeholder="Enter state" class="p-3 rounded bg-[#f3efee] text-black" required>
                        <input type="text" name="billing[city]" placeholder="Enter City" class="p-3 rounded bg-[#f3efee] text-black" required>
                        <input type="text" name="billing[postcode]" placeholder="Enter Zip/Postcode" class="p-3 rounded bg-[#f3efee] text-black" required>
                    </div>
                </div>

                <!-- PAYMENT -->
                <div>
                    <h2 class="text-2xl font-semibold uppercase mb-6 text-[#c07a3a]">Payment</h2>
                    <div class="space-y-5">
                        <input type="text" name="payment[cc_number]" placeholder="Card number" class="w-full p-3 rounded bg-[#f3efee] text-black" required>
                        <input type="text" name="payment[cc_name]" placeholder="Name on card" class="w-full p-3 rounded bg-[#f3efee] text-black" required>
                        <div class="grid grid-cols-2 gap-6">
                            <input type="text" name="payment[cc_exp_month]" placeholder="mm" class="p-3 rounded bg-[#f3efee] text-black" required>
                            <input type="text" name="payment[cc_exp_year]" placeholder="yyyy" class="p-3 rounded bg-[#f3efee] text-black" required>
                        </div>
                        <input type="text" name="payment[cc_cvv]" placeholder="CVV" class="w-full p-3 rounded bg-[#f3efee] text-black" required>
                    </div>
                </div>

            </div>

            <!-- RIGHT SIDE: Order Summary -->
            <div class="col-span-5">
                <div class="bg-[#f3efee] rounded-xl p-8 text-black">
                    <h3 class="text-lg font-semibold uppercase mb-6">Order Summary</h3>

                    <div class="space-y-4">
                        @foreach($cartItems as $item)
                            <div class="flex items-center gap-4">
                                <img src="{{ $item->product->base_image_url }}" class="w-14 h-14 rounded object-cover">
                                <div>
                                    <p class="font-semibold">{{ $item->product->name }}</p>
                                    <p class="text-sm text-gray-500">{{ $item->quantity }} x {{ core()->currency($item->product->price) }}</p>
                                </div>
                                <div class="ml-auto text-[#c07a3a] font-semibold">
                                    {{ core()->currency($item->product->price * $item->quantity) }}
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="space-y-3 text-sm border-t pt-4">
                        <div class="flex justify-between"><span>Subtotal</span><span>{{ core()->currency($subtotal) }}</span></div>
                        <div class="flex justify-between text-green-600"><span>Discounts</span><span>-{{ core()->currency($discount) }}</span></div>
                        <div class="flex justify-between"><span>Tax</span><span>{{ core()->currency($subtotal * 0.05) }}</span></div>
                    </div>

                    <div class="flex justify-between text-lg font-bold border-t mt-4 pt-4">
                        <span>Total</span>
                        <span class="text-[#c07a3a]">{{ core()->currency($total) }}</span>
                    </div>

                    <div class="flex justify-center mt-12">
                        <button type="submit" class="bg-[#d9a37c] text-black px-10 py-3 rounded-full uppercase font-semibold">
                            Proceed
                        </button>
                    </div>
                </div>
            </div>

        </div>
    </form>

</section>

@endsection