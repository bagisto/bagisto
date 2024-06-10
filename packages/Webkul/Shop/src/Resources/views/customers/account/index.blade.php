<x-shop::layouts.account>
    <!-- Page Title -->
    <x-slot:title>
        @lang('shop::app.customers.account.orders.title')
    </x-slot>

    <!-- Breadcrumbs -->
    @section('breadcrumbs')
        <x-shop::breadcrumbs name="orders" />
    @endSection

    <div class="mx-4">
        <x-shop::layouts.account.navigation />
    </div>

    <span class="mb-5 mt-2 w-full border-t border-zinc-300"></span>

    <!--Customers logout-->
    @auth('customer')
        <div class="mx-4">
            <div class="w-full rounded-lg border border-navyBlue py-1.5 text-center">
                <x-shop::form
                    method="DELETE"
                    action="{{ route('shop.customer.session.destroy') }}"
                    id="customerLogout"
                />

                <a
                    class="flex items-center justify-center gap-1.5 text-base hover:bg-gray-100"
                    href="{{ route('shop.customer.session.destroy') }}"
                    onclick="event.preventDefault(); document.getElementById('customerLogout').submit();"
                >
                    @lang('shop::app.components.layouts.header.logout')
                </a>
            </div>
        </div>
    @endauth

</x-shop::layouts.accounts>