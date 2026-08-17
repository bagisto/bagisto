<x-shop::layouts.account>
    <x-slot:title>
        @lang('shop::app.eu_withdrawal.confirmation.page_title')
    </x-slot>

    @if ((core()->getConfigData('general.general.breadcrumbs.shop')))
        @section('breadcrumbs')
            <x-shop::breadcrumbs name="account.eu-withdrawal.show" />
        @endSection
    @endif

    <div class="max-md:hidden">
        <x-shop::layouts.account.navigation />
    </div>

    <div class="mx-4 flex-auto max-md:mx-6 max-sm:mx-4">
        @include('shop::customers.account.eu-withdrawals.receipt', ['withdrawal' => $withdrawal, 'isGuest' => false])
    </div>
</x-shop::layouts.account>
