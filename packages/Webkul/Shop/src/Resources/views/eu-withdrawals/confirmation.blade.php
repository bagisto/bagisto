<x-shop::layouts>
    <x-slot:title>
        @lang('shop::app.eu_withdrawal.confirmation.page_title')
    </x-slot>

    <div class="container mt-10 mx-auto max-w-3xl px-5 max-md:mt-6 max-md:px-4">
        @include('shop::customers.account.eu-withdrawals.receipt', ['withdrawal' => $withdrawal, 'isGuest' => true])
    </div>
</x-shop::layouts>
