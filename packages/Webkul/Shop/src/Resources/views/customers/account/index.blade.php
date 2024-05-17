<x-shop::layouts.account>
    <!-- Page Title -->
    <x-slot:title>
        @lang('shop::app.customers.account.orders.title')
    </x-slot>

    <!-- Breadcrumbs -->
    @section('breadcrumbs')
        <x-shop::breadcrumbs name="orders" />
    @endSection

    <x-shop::layouts.account.navigation />

    <!--Customers logout-->
    @auth('customer')
        <div class="mt-3 w-full rounded-md border border-navyBlue py-2 text-center">
            <x-shop::form
                method="DELETE"
                action="{{ route('shop.customer.session.destroy') }}"
                id="customerLogout"
            />

            <a
                class="cursor-pointer px-5 py-2 text-base hover:bg-gray-100"
                href="{{ route('shop.customer.session.destroy') }}"
                onclick="event.preventDefault(); document.getElementById('customerLogout').submit();"
            >
                @lang('shop::app.components.layouts.header.logout')
            </a>
        </div>
    @endauth

</x-shop::layouts.accounts>