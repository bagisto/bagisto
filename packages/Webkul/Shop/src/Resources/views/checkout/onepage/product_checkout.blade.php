@extends('shop::layouts.master')

@section('main-content')

<section class="max-w-7xl mx-auto px-4 py-20 font-oswald text-[#2a1f14]">

<form action="{{ route('shop.checkout.product') }}" method="POST">
@csrf

<div class="grid grid-cols-12 gap-12">

<!-- LEFT COLUMN: FORM -->
<div class="col-span-7 space-y-14">

<!-- BILLING ADDRESS -->
<div class="bg-white shadow-md rounded-xl p-8">
    <h2 class="text-2xl font-semibold mb-6 border-b border-gray-200 pb-2 uppercase">
        Billing Address
    </h2>

    <div class="grid grid-cols-2 gap-6">
        <div>
            <label class="form-label">First Name</label>
            <input type="text" name="billing[first_name]" value="{{ old('billing.first_name') }}"
                class="form-input w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-1 focus:ring-[#d9a37c]">
        </div>

        <div>
            <label class="form-label">Last Name</label>
            <input type="text" name="billing[last_name]" value="{{ old('billing.last_name') }}"
                class="form-input w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-1 focus:ring-[#d9a37c]">
        </div>

        <div>
            <label class="form-label">Email</label>
            <input type="email" name="billing[email]" value="{{ old('billing.email') }}"
                class="form-input w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-1 focus:ring-[#d9a37c]">
        </div>

        <div>
            <label class="form-label">Telephone</label>
            <input type="text" name="billing[phone]" value="{{ old('billing.phone') }}"
                class="form-input w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-1 focus:ring-[#d9a37c]">
        </div>

        <div class="col-span-2">
            <label class="form-label">Street Address</label>
            <input type="text" name="billing[address][0]" value="{{ old('billing.address.0') }}"
                class="form-input w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-1 focus:ring-[#d9a37c]">
        </div>

        <div>
            <label class="form-label">Country</label>
            <select name="billing[country]" class="form-input w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-1 focus:ring-[#d9a37c]">
                <option value="">Select Country</option>
                @foreach(core()->countries() as $country)
                    <option value="{{ $country->code }}" {{ old('billing.country') == $country->code ? 'selected' : '' }}>
                        {{ $country->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="form-label">State</label>
            <input type="text" name="billing[state]" value="{{ old('billing.state') }}"
                class="form-input w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-1 focus:ring-[#d9a37c]">
        </div>

        <div>
            <label class="form-label">City</label>
            <input type="text" name="billing[city]" value="{{ old('billing.city') }}"
                class="form-input w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-1 focus:ring-[#d9a37c]">
        </div>

        <div>
            <label class="form-label">Zip/Postcode</label>
            <input type="text" name="billing[postcode]" value="{{ old('billing.postcode') }}"
                class="form-input w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-1 focus:ring-[#d9a37c]">
        </div>
    </div>

    <div class="flex items-center gap-3 mt-6">
        <input type="checkbox" id="same_as_billing" name="same_as_billing" value="1" checked class="accent-[#d9a37c]">
        <label for="same_as_billing" class="text-gray-700 font-medium">Use same address for shipping</label>
    </div>
</div>

<!-- SHIPPING ADDRESS -->
<div id="shipping-form" class="bg-white shadow-md rounded-xl p-8 mt-6" style="display:none;">
    <h2 class="text-2xl font-semibold mb-6 border-b border-gray-200 pb-2 uppercase">Shipping Address</h2>

    <div class="grid grid-cols-2 gap-6">
        <div>
            <label class="form-label">First Name</label>
            <input type="text" name="shipping[first_name]" value="{{ old('shipping.first_name') }}"
                class="form-input w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-1 focus:ring-[#d9a37c]">
        </div>

        <div>
            <label class="form-label">Last Name</label>
            <input type="text" name="shipping[last_name]" value="{{ old('shipping.last_name') }}"
                class="form-input w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-1 focus:ring-[#d9a37c]">
        </div>

        <div>
            <label class="form-label">Email</label>
            <input type="email" name="shipping[email]" value="{{ old('shipping.email') }}"
                class="form-input w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-1 focus:ring-[#d9a37c]">
        </div>

        <div>
            <label class="form-label">Telephone</label>
            <input type="text" name="shipping[phone]" value="{{ old('shipping.phone') }}"
                class="form-input w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-1 focus:ring-[#d9a37c]">
        </div>

        <div class="col-span-2">
            <label class="form-label">Street Address</label>
            <input type="text" name="shipping[address][0]" value="{{ old('shipping.address.0') }}"
                class="form-input w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-1 focus:ring-[#d9a37c]">
        </div>

        <div>
            <label class="form-label">Country</label>
            <select name="shipping[country]" class="form-input w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-1 focus:ring-[#d9a37c]">
                <option value="">Select Country</option>
                @foreach(core()->countries() as $country)
                    <option value="{{ $country->code }}" {{ old('shipping.country') == $country->code ? 'selected' : '' }}>
                        {{ $country->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="form-label">State</label>
            <input type="text" name="shipping[state]" value="{{ old('shipping.state') }}"
                class="form-input w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-1 focus:ring-[#d9a37c]">
        </div>

        <div>
            <label class="form-label">City</label>
            <input type="text" name="shipping[city]" value="{{ old('shipping.city') }}"
                class="form-input w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-1 focus:ring-[#d9a37c]">
        </div>

        <div>
            <label class="form-label">Zip/Postcode</label>
            <input type="text" name="shipping[postcode]" value="{{ old('shipping.postcode') }}"
                class="form-input w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-1 focus:ring-[#d9a37c]">
        </div>
    </div>
</div>

<!-- PAYMENT METHOD -->
<div class="bg-white shadow-md rounded-xl p-8 mt-6">
    <h2 class="text-2xl font-semibold mb-6 border-b border-gray-200 pb-2 uppercase">Payment Method</h2>

    <div class="space-y-4">
        @foreach($methods['payment_methods'] as $method)
        <label class="flex items-center gap-3 border rounded-lg p-4 cursor-pointer hover:border-[#d9a37c]">
            <input type="radio" name="payment[method]" value="{{ $method['method'] }}"
                class="accent-[#d9a37c]" {{ old('payment.method') == $method['method'] ? 'checked' : '' }}>
            <span class="text-sm">{{ $method['method_title'] }}</span>
        </label>
        @endforeach
    </div>
</div>

<div class="flex justify-center mt-12">
    <button type="submit"
        class="bg-[#c07a3a] hover:bg-[#a8652f] text-white uppercase font-semibold px-8 py-3 rounded-full">
        Proceed
    </button>
</div>

</div>

<!-- RIGHT COLUMN: ORDER SUMMARY -->
<div class="col-span-5">
    <div class="bg-[#f3efee] rounded-xl p-8 shadow-md">
        <h3 class="text-lg font-semibold uppercase mb-6">Order Summary</h3>

        @foreach($cartItems as $item)
        <div class="flex items-center gap-4 mb-3">
            <img src="{{ $item->product->base_image_url }}" class="w-14 h-14 rounded object-cover">

            <div>
                <p class="font-semibold">{{ $item->product->name }}</p>
                <p class="text-sm text-gray-500">{{ $item->quantity }} × {{ core()->currency($item->product->price) }}</p>
            </div>

            <div class="ml-auto text-[#c07a3a] font-semibold">
                {{ core()->currency($item->product->price * $item->quantity) }}
            </div>
        </div>
        @endforeach

        <div class="flex justify-between mt-4 text-lg font-semibold">
            <span>Total</span>
            <span class="text-[#c07a3a] font-bold">{{ core()->currency($total) }}</span>
        </div>
    </div>
</div>

</div>
</form>
</section>

<script>
let checkbox = document.getElementById("same_as_billing")
let shippingForm = document.getElementById("shipping-form")

checkbox.addEventListener("change", function(){
    shippingForm.style.display = this.checked ? "none" : "block"
});
</script>

@endsection