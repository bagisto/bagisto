{{ $address->name }}</br>
{{ $address->address1 }}</br>
{{ $address->city }}</br>
 {{ $address->state }}</br>
{{ country()->name($address->country) }} {{ $address->postcode }}</br></br>
{{ __('shop::app.checkout.onepage.contact') }} : {{ $address->phone }} 