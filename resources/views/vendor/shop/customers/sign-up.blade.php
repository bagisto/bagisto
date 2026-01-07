{{-- RAM Plaza - Customer Registration Redirect --}}
{{-- Registration is handled by RAM (RedActivaMexico) --}}
{{-- This page redirects to login, which triggers OAuth flow --}}
{{-- Override: resources/views/vendor/shop/customers/sign-up.blade.php --}}

@php
    // Redirect to login page - RAM handles registration through OAuth flow
    header('Location: ' . route('shop.customer.session.index'));
    exit;
@endphp
