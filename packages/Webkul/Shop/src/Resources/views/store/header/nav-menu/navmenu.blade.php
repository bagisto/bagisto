{{-- <ul class="menu-bar">
    <category-nav categories='@json($categories)' url="{{url()->to('/')}}"></category-nav>
    <li>
        <img class="icon" src="vendor/webkul/shop/assets/images/offer-zone.svg" style="margin-right:5px;" />Offer Zone
    </li>
    <li>Men</li>
    <li>Women</li>
    <li>Kids</li>
    <li>Accessories</li>
    <li>Home & Living</li>

</ul> --}}
<category-nav categories='@json($categories)' url="{{url()->to('/')}}"></category-nav>
{{-- <nav-view items='@json($categories)'></nav-view> --}}
