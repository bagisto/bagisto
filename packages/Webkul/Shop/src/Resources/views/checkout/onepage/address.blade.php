{!! view_render_event('bagisto.shop.checkout.onepage.address.before') !!}

<!-- Accordian Blade Component -->
<x-shop::accordion class="mb-7 mt-8 !border-b-0">
    <!-- Accordian Header Component Slot -->
    <x-slot:header class="!p-0">
        <div class="flex items-center justify-between">
            <h2 class="text-2xl font-medium max-sm:text-xl">
                @lang('shop::app.checkout.onepage.address.title')
            </h2>
        </div>
    </x-slot>

    <!-- Accordian Content Component Slot -->
    <x-slot:content class="mt-8 !p-0">
        <!-- If the customer is guest -->
        <template v-if="cart.is_guest">
            @include('shop::checkout.onepage.address.guest')
        </template>

        <!-- If the customer is logged in -->
        <template v-else>
            @include('shop::checkout.onepage.address.customer')
        </template>
    </x-slot:content>
</x-shop::accordion>

{!! view_render_event('bagisto.shop.checkout.onepage.address.after') !!}