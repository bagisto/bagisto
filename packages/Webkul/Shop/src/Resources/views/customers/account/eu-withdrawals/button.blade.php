@php
    $euWithdrawalChannelCode = optional($order->channel)->code;
    
    $euWithdrawalEnabled = (bool) core()->getConfigData(
        'sales.eu_withdrawal.general.enabled',
        $euWithdrawalChannelCode
    );

    $existingWithdrawal = $euWithdrawalEnabled
        ? app(\Webkul\EUWithdrawal\Repositories\WithdrawalRepository::class)->findForOrder($order->id)
        : null;
@endphp

@if ($euWithdrawalEnabled)
    @if ($existingWithdrawal)
        <a
            class="{{ $variant ?? 'secondary-button border-zinc-200 px-5 py-3 font-normal max-md:hidden' }}"
            href="{{ route('shop.customers.account.eu-withdrawal.show', $existingWithdrawal->uuid) }}"
        >
            @lang('shop::app.eu_withdrawal.button.view_existing')
        </a>
    @else
        <a
            class="{{ $variant ?? 'primary-button px-5 py-3 font-normal max-md:hidden' }}"
            href="{{ route('shop.customers.account.eu-withdrawal.create', $order->id) }}"
        >
            @lang('shop::app.eu_withdrawal.button.withdraw')
        </a>
    @endif
@endif
