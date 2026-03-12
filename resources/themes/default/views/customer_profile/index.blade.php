@extends('shop::layouts.master')

@section('main-content')

<section class="max-w-7xl mx-auto px-4 py-24 font-oswald text-[#2a1f14]">

<h2 class="text-3xl uppercase mb-12">My Profile</h2>

<div class="grid grid-cols-12 gap-12">

<!-- LEFT SIDE -->
<div class="col-span-7 flex flex-col space-y-8">

<!-- Profile Info -->
<div class="flex items-center gap-6 border-b border-gray-200 pb-6">

<img 
src="{{ $customer->image ?? asset('images/account.png') }}"
class="w-28 h-28 rounded-full object-cover">

<div>
<h3 class="text-2xl uppercase">
  {{ $customer->first_name  . " " . $customer->last_name  ?? "John Doe" }}
</h3>

<p class="text-gray-500 text-sm">
 {{ $custome->remail ?? "john@example.com" }}
</p>

<p class="text-gray-500 text-sm">
{{ $customer->phone ?? "+971 123 456 789" }}
</p>
</div>

</div>


<!-- Account Details -->
<div>

<h3 class="text-xl uppercase mb-6">Account Details</h3>

<div class="grid grid-cols-2 gap-6">

<div>
<label class="text-sm text-gray-500">First Name</label>
<p class="text-lg">
  {{ $customer->first_name  ?? "John" }}
</p>
</div>

<div>
<label class="text-sm text-gray-500">Last Name</label>
<p class="text-lg">
  {{ $customer->last_name  ?? "Doew" }}
</p>
</div>

<div>
<label class="text-sm text-gray-500">Email</label>
<p class="text-lg">
 {{ $custome->remail ?? "john@example.com" }}
</p>
</div>

<div>
<label class="text-sm text-gray-500">Phone</label>
<p class="text-lg">
{{ $customer->phone ?? "+971 123 456 789" }}
</p>
</div>

</div>

</div>


<!-- Address -->
<div>

<h3 class="text-xl uppercase mb-6">Address</h3>

<div class="border border-gray-200 p-6 rounded-lg">

<p>Abu Dhabi Marina</p>
<p>Apartment 1203</p>
<p>United Arab Emirates</p>

</div>

</div>

</div>


<!-- RIGHT SIDE -->
<div class="col-span-5 bg-gray-50 rounded-lg p-8 shadow-lg h-fit">

<h3 class="text-xl font-semibold uppercase mb-6 border-b border-gray-300 pb-4">
Account
</h3>

<div class="flex flex-col gap-4">

<button
class="w-full border border-gray-300 py-3 rounded-full hover:bg-gray-100">
Edit Profile
</button>

<button
class="w-full border border-gray-300 py-3 rounded-full hover:bg-gray-100">
My Orders
</button>

<button
class="w-full border border-gray-300 py-3 rounded-full hover:bg-gray-100">
My Addresses
</button>

<button
class="w-full bg-[#c07a3a] text-white py-3 rounded-full hover:bg-[#a8652f] transition">
Logout
</button>

</div>

</div>

</div>

</section>

@endsection