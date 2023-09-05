@if ($prices['final']['price'] < $prices['regular']['price'])
    <p class="font-medium text-[#7D7D7D] line-through">{{ $prices['regular']['formatted_price'] }}</p>
    <p class="font-semibold">{{ $prices['final']['formatted_price'] }}</p>
@else
    <p class="font-semibold">{{ $prices['regular']['formatted_price'] }}</p>
@endif