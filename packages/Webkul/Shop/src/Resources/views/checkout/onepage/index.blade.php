@extends('shop::layouts.master')

@section('main-content')

<style>
.form-label{
font-family:'Oswald',sans-serif;
font-weight:400;
font-size:20px;
line-height:100%;
letter-spacing:10%;
text-transform:uppercase;
color:#371E0F;
margin-bottom:6px;
display:block;
}

.form-input{
width:100%;
height:52px;
border-radius:8px;
border:1px solid #E2E2E2;
padding:14px 24px;
font-family:'Roboto',sans-serif;
font-size:16px;
line-height:24px;
}

.form-input::placeholder{
color:#E2E2E2;
font-family:'Roboto',sans-serif;
font-size:16px;
}

.address-heading{
font-family:'Oswald',sans-serif;
font-weight:400;
font-size:36px;
line-height:100%;
text-transform:uppercase;
color:#371E0F;
margin-bottom:24px;
}

.form-input:focus{
outline:none;
border-color:#d9a37c;
box-shadow:0 0 0 1px #d9a37c;
}

.error{
color:red;
font-size:14px;
margin-top:4px;
}
</style>

<section class="max-w-7xl mx-auto px-4 py-20">

<form id="checkoutForm" action="{{ route('shop.checkout.service') }}" method="POST">

@csrf

<input type="hidden" name="booking_date" id="booking_date">
<input type="hidden" name="booking_time" id="booking_time">

<div class="grid grid-cols-12 gap-12">

<!-- LEFT SIDE -->
<div class="col-span-7 space-y-14">

<!-- DATE & TIME -->
<div>
<h2 class="text-2xl font-semibold uppercase mb-6 text-[#371E0F]">
SELECT DATE & TIME
</h2>

<div class="bg-[#f3efee] rounded-2xl p-8 text-black">

<div class="flex items-center justify-between mb-8">
<select id="month-select" class="bg-white border rounded-lg px-4 py-2 text-sm"></select>

<div class="flex gap-3">
<button type="button" id="prev-week"
class="w-10 h-10 rounded-full border flex items-center justify-center">←</button>
<button type="button" id="next-week"
class="w-10 h-10 rounded-full border border-[#d9a37c] text-[#d9a37c] flex items-center justify-center">→</button>
</div>
</div>

<div id="calendar-days" class="grid grid-cols-7 gap-4 mb-8"></div>
<div id="time-slots" class="grid grid-cols-5 gap-3"></div>
<span id="dateTimeError" class="error"></span>

</div>
</div>

<!-- ADDRESS -->
<div>
<h2 class="address-heading">Address</h2>
<div class="grid grid-cols-2 gap-6">

<div>
<label class="form-label">First Name</label>
<input type="text" name="billing[first_name]" placeholder="Enter first name" class="form-input" required>
<span class="error" id="first_name_error"></span>
</div>

<div>
<label class="form-label">Last Name</label>
<input type="text" name="billing[last_name]" placeholder="Enter last name" class="form-input" required>
<span class="error" id="last_name_error"></span>
</div>

<div>
<label class="form-label">Email</label>
<input type="email" name="billing[email]" placeholder="email@example.com" class="form-input" required>
<span class="error" id="email_error"></span>
</div>

<div>
<label class="form-label">Telephone</label>
<input type="text" name="billing[phone]" placeholder="Enter telephone" class="form-input" required>
<span class="error" id="phone_error"></span>
</div>

<div class="col-span-2">
<label class="form-label">Street Address</label>
<input type="text" name="billing[address1]" placeholder="Enter street address" class="form-input" required>
<span class="error" id="address1_error"></span>
</div>

<div>
<label class="form-label">Country</label>
<select name="billing[country]" class="form-input" required>
<option value="">Select Country</option>
@foreach(core()->countries() as $country)
<option value="{{ $country->code }}">{{ $country->name }}</option>
@endforeach
</select>
<span class="error" id="country_error"></span>
</div>

<div>
<label class="form-label">State</label>
<input type="text" name="billing[state]" placeholder="Enter state" class="form-input" required>
<span class="error" id="state_error"></span>
</div>

<div>
<label class="form-label">City</label>
<input type="text" name="billing[city]" placeholder="Enter City" class="form-input" required>
<span class="error" id="city_error"></span>
</div>

<div>
<label class="form-label">Zip/Postcode</label>
<input type="text" name="billing[postcode]" placeholder="Enter Zip/Postcode" class="form-input" required>
<span class="error" id="postcode_error"></span>
</div>

</div>
</div>

<!-- PAYMENT METHOD -->
<div>
<h2 class="address-heading">Payment Method</h2>
<div class="space-y-4">

@foreach(app('Webkul\Payment\Payment')->getPaymentMethods() as $method)
@php
$code = is_array($method) ? ($method['method'] ?? $method['code']) : $method->getCode();
$title = is_array($method) ? ($method['method_title'] ?? $method['title']) : $method->getTitle();
@endphp

<label class="flex items-center gap-3 border rounded-lg p-4 cursor-pointer hover:border-[#d9a37c]">
<input type="radio" name="payment[method]" value="{{ $code }}" class="accent-[#d9a37c]" required>
<span class="text-sm">{{ $title }}</span>
</label>
@endforeach

<span class="error" id="payment_error"></span>

</div>
</div>

<div class="flex justify-center mt-12">
<button type="submit"
class="border border-[#d9a37c] text-[#371E0F] px-8 py-3 rounded-full uppercase font-semibold">
Proceed
</button>
</div>

</div>

<!-- RIGHT SIDE -->
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
<div class="ml-auto text-[#c07a3a] font-semibold">{{ core()->currency($item->product->price * $item->quantity) }}</div>
</div>
@endforeach
</div>

<div class="space-y-3 text-sm border-t pt-4 mt-6">
<div class="flex justify-between"><span>Subtotal</span><span>{{ core()->currency($subtotal) }}</span></div>
<div class="flex justify-between text-green-600"><span>Discounts</span><span>-{{ core()->currency($discount) }}</span></div>
<div class="flex justify-between"><span>Tax</span><span>{{ core()->currency($subtotal * 0.05) }}</span></div>
</div>

<div class="flex justify-between text-lg font-bold border-t mt-4 pt-4">
<span>Total</span>
<span class="text-[#c07a3a]">{{ core()->currency($total) }}</span>
</div>

</div>
</div>

</div>

</form>

</section>

@endsection

<script>
document.addEventListener('DOMContentLoaded', () => {

const availability = @json($availability);
const productId = {{ $cartItems->first()->product->id ?? 'null' }};
const slotsPerDay = availability[productId] || {};

const calendar = document.getElementById('calendar-days');
const timeSlots = document.getElementById('time-slots');
const monthSelect = document.getElementById('month-select');

let currentDate = new Date();
let weekOffset = 0;
let selectedDate = null;
let selectedSlot = null;

/* ---------- MONTH DROPDOWN ---------- */
function loadMonths(){
monthSelect.innerHTML = "";
for(let i=0;i<12;i++){
let d = new Date(currentDate.getFullYear(),i);
let option = document.createElement("option");
option.value = i;
option.text = d.toLocaleString("default",{month:"long"}) + " " + currentDate.getFullYear();
monthSelect.appendChild(option);
}
monthSelect.value = currentDate.getMonth();
}

/* ---------- SLOT COUNT ---------- */
function countSlots(slots){
let total = 0;
slots.forEach(group=>{ group.forEach(slot=>{ total++; }); });
return total;
}

/* ---------- RENDER CALENDAR ---------- */
function renderCalendar(){
calendar.innerHTML = "";
const today = new Date();
const year = currentDate.getFullYear();
const month = currentDate.getMonth();
const firstDay = new Date(year,month,1);
firstDay.setDate(firstDay.getDate() + (weekOffset * 7));

for(let i=0;i<7;i++){
let date = new Date(firstDay);
date.setDate(firstDay.getDate() + i);
if(date.getMonth() !== month) continue;

let isoDate = date.toISOString().split("T")[0];
let slots = slotsPerDay[isoDate] || [];
let slotCount = countSlots(slots);
let isPast = date < new Date(today.setHours(0,0,0,0));
let weekday = date.toLocaleString("default",{weekday:"short"}).toUpperCase();
let day = date.getDate();

let card = document.createElement("div");
card.className = `border rounded-xl p-4 text-center transition 
${slotCount===0 || isPast ? "opacity-40 cursor-not-allowed" : "cursor-pointer"}
${selectedDate===isoDate ? "border-[#d9a37c]" : "border-gray-200"}
hover:border-[#d9a37c]`;

card.innerHTML = `<div class="text-xs text-gray-500 mb-1">${weekday}</div>
<div class="text-lg font-semibold">${day}</div>
<div class="text-xs text-gray-400">${slotCount} Slots</div>`;

if(!isPast && slotCount>0){
card.onclick = () => {
selectedDate = isoDate;
renderCalendar();
renderSlots(slots);
};
}

calendar.appendChild(card);
}

/* Update hidden input */
document.getElementById('booking_date').value = selectedDate || '';
}

/* ---------- RENDER SLOTS ---------- */
function renderSlots(slots){
timeSlots.innerHTML = "";
slots.forEach(group=>{
group.forEach(slot=>{
let btn = document.createElement("button");
btn.className = "border rounded-lg py-2 text-sm hover:border-[#d9a37c] transition";
btn.textContent = formatTime(slot.from);
btn.onclick = () => {
selectedSlot = slot.from;
document.querySelectorAll("#time-slots button").forEach(b=>b.classList.remove("bg-[#d9a37c]","text-white"));
btn.classList.add("bg-[#d9a37c]","text-white");
document.getElementById('booking_time').value = selectedSlot;
};
timeSlots.appendChild(btn);
});
});
}

/* ---------- TIME FORMAT ---------- */
function formatTime(time){
let [h,m] = time.split(":");
h = parseInt(h);
let ampm = h>=12 ? "PM" : "AM";
h = h % 12 || 12;
return `${h}:${m} ${ampm}`;
}

/* ---------- AUTO SELECT FIRST DATE ---------- */
function selectFirstAvailable(){
let keys = Object.keys(slotsPerDay);
if(keys.length){
selectedDate = keys[0];
renderSlots(slotsPerDay[selectedDate]);
document.getElementById('booking_date').value = selectedDate;
}
}

/* ---------- EVENTS ---------- */
monthSelect.addEventListener("change",()=>{
currentDate.setMonth(monthSelect.value);
weekOffset = 0;
renderCalendar();
});

document.getElementById("next-week").addEventListener("click",()=>{
weekOffset++;
renderCalendar();
});

document.getElementById("prev-week").addEventListener("click",()=>{
weekOffset--;
if(weekOffset<0) weekOffset=0;
renderCalendar();
});

/* ---------- INIT ---------- */
loadMonths();
renderCalendar();
selectFirstAvailable();

/* ---------- FRONTEND VALIDATION ---------- */
document.getElementById('checkoutForm').addEventListener('submit', function(e){
let valid = true;

// Clear previous errors
document.querySelectorAll('.error').forEach(el=>el.textContent='');

// Validate date & time
if(!selectedDate || !selectedSlot){
document.getElementById('dateTimeError').textContent = "Please select date & time";
valid = false;
}

// First Name
let firstName = this.querySelector('input[name="billing[first_name]"]').value.trim();
if(!firstName){
document.getElementById('first_name_error').textContent = "First name is required";
valid = false;
}

// Last Name
let lastName = this.querySelector('input[name="billing[last_name]"]').value.trim();
if(!lastName){
document.getElementById('last_name_error').textContent = "Last name is required";
valid = false;
}

// Email
let email = this.querySelector('input[name="billing[email]"]').value.trim();
let emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
if(!email){
document.getElementById('email_error').textContent = "Email is required";
valid = false;
}else if(!emailPattern.test(email)){
document.getElementById('email_error').textContent = "Invalid email address";
valid = false;
}

// Phone
let phone = this.querySelector('input[name="billing[phone]"]').value.trim();
if(!phone){
document.getElementById('phone_error').textContent = "Telephone is required";
valid = false;
}

// Address1
let address1 = this.querySelector('input[name="billing[address1]"]').value.trim();
if(!address1){
document.getElementById('address1_error').textContent = "Street address is required";
valid = false;
}

// Country
let country = this.querySelector('select[name="billing[country]"]').value;
if(!country){
document.getElementById('country_error').textContent = "Please select country";
valid = false;
}

// State
let state = this.querySelector('input[name="billing[state]"]').value.trim();
if(!state){
document.getElementById('state_error').textContent = "State is required";
valid = false;
}

// City
let city = this.querySelector('input[name="billing[city]"]').value.trim();
if(!city){
document.getElementById('city_error').textContent = "City is required";
valid = false;
}

// Postcode
let postcode = this.querySelector('input[name="billing[postcode]"]').value.trim();
if(!postcode){
document.getElementById('postcode_error').textContent = "Zip/Postcode is required";
valid = false;
}

// Payment method
let payment = this.querySelector('input[name="payment[method]"]:checked');
if(!payment){
document.getElementById('payment_error').textContent = "Please select a payment method";
valid = false;
}

if(!valid){
e.preventDefault();
}
});
});
</script>