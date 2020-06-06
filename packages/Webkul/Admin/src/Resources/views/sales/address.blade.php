{{ $address->name }}</br>
{{ $address->address1 }}</br>
{{ $address->city }}</br>
 {{ $address->state }}</br>
{{ core()->country_name($address->country) }} {{ $address->postcode }}</br></br>
{{ __('shop::app.checkout.onepage.contact') }} : {{ $address->phone }} 