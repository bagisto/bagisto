<template>
    <wishlist-component-with-badge is-customer="{{ auth()->guard('customer')->check() ? 'true' : 'false' }}" is-text=true src="{{ route('customer.wishlist.index') }}" ></wishlist-component-with-badge>
</template>

<script type='text/javascript'>
    export default {
        mounted: function() {
            console.log("Header Component is mounted");
        }
    }
</script>