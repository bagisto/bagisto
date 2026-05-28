{{--
    Shared withdrawal receipt presentation.
    Used by both the auth-customer confirmation page and the guest signed-URL page.
    $withdrawal (Withdrawal model), $isGuest (bool).
--}}
@php
    $isDeclined = $withdrawal->status === 'declined';

    $statusContext = [
        'received' => [
            'badge'   => 'bg-amber-100 text-amber-800',
            'hero_bg' => 'bg-emerald-100 text-emerald-700',
        ],

        'refunded' => [
            'badge'   => 'bg-emerald-100 text-emerald-800',
            'hero_bg' => 'bg-emerald-100 text-emerald-700',
        ],

        'declined' => [
            'badge'   => 'bg-red-100 text-red-800',
            'hero_bg' => 'bg-red-100 text-red-700',
        ],
    ][$withdrawal->status] ?? [
        'badge'   => 'bg-zinc-100 text-zinc-800',
        'hero_bg' => 'bg-zinc-100 text-zinc-700',
    ];
@endphp

<div class="flex items-center justify-between max-sm:flex-col max-sm:items-start max-sm:gap-3">
    <div class="flex items-center gap-3">
        <span class="grid h-10 w-10 flex-shrink-0 place-items-center rounded-full {{ $statusContext['hero_bg'] }}">
            @if ($isDeclined)
                <span class="text-2xl font-bold leading-none">&times;</span>
            @else
                <span class="text-2xl icon-check-box"></span>
            @endif
        </span>

        <div>
            <h2 class="text-2xl font-medium max-md:text-xl max-sm:text-base">
                @lang('shop::app.eu_withdrawal.confirmation.heading_'.$withdrawal->status)
            </h2>

            <p class="text-sm text-zinc-600 max-sm:hidden">
                @lang('shop::app.eu_withdrawal.confirmation.intro_'.$withdrawal->status)
            </p>
        </div>
    </div>

    <button
        type="button"
        onclick="window.print()"
        class="secondary-button border-zinc-200 px-4 py-2 text-sm font-normal max-md:rounded-lg print:hidden"
    >
        @lang('shop::app.eu_withdrawal.confirmation.print')
    </button>
</div>

<p class="mt-3 text-sm text-zinc-600 sm:hidden">
    @lang('shop::app.eu_withdrawal.confirmation.intro_'.$withdrawal->status)
</p>

{{-- Receipt Card --}}
<div class="mt-8 overflow-hidden rounded-xl border border-zinc-200 bg-white">
    <div class="flex items-center justify-between border-b border-zinc-100 bg-zinc-50 px-6 py-4">
        <div>
            <p class="text-xs font-medium uppercase tracking-wide text-zinc-500">
                @lang('shop::app.eu_withdrawal.confirmation.reference')
            </p>

            <p class="mt-1 font-mono text-sm font-medium text-zinc-900">
                {{ $withdrawal->uuid }}
            </p>
        </div>

        <div class="text-right">
            <p class="text-xs font-medium uppercase tracking-wide text-zinc-500">
                @lang('shop::app.eu_withdrawal.confirmation.status')
            </p>

            <span class="mt-1 inline-flex items-center rounded-full px-3 py-1 text-xs font-medium {{ $statusContext['badge'] }}">
                @lang('shop::app.eu_withdrawal.confirmation.status_'.$withdrawal->status)
            </span>
        </div>
    </div>

    <dl class="grid grid-cols-1 divide-y divide-zinc-100 sm:grid-cols-2 sm:divide-x sm:divide-y-0">
        <div class="px-6 py-4">
            <dt class="text-xs font-medium uppercase tracking-wide text-zinc-500">
                @lang('shop::app.eu_withdrawal.confirmation.received_at')
            </dt>

            <dd class="mt-1 text-sm font-medium text-zinc-900">
                {{ $withdrawal->received_at->copy()->setTimezone('UTC')->format('d M Y, H:i') }}
                <span class="text-zinc-500">UTC</span>
            </dd>
        </div>

        <div class="px-6 py-4">
            <dt class="text-xs font-medium uppercase tracking-wide text-zinc-500">
                @lang('shop::app.eu_withdrawal.confirmation.order')
            </dt>

            <dd class="mt-1 text-sm font-medium text-zinc-900">
                @if (! $isGuest)
                    <a
                        href="{{ route('shop.customers.account.orders.view', $withdrawal->order_id) }}"
                        class="text-navyBlue hover:underline"
                    >
                        #{{ $withdrawal->order->increment_id ?? $withdrawal->order_id }}
                    </a>
                @else
                    #{{ $withdrawal->order->increment_id ?? $withdrawal->order_id }}
                @endif
            </dd>
        </div>

        <div class="px-6 py-4 sm:col-span-2 sm:border-t sm:border-zinc-100">
            <dt class="text-xs font-medium uppercase tracking-wide text-zinc-500">
                @lang('shop::app.eu_withdrawal.confirmation.email')
            </dt>

            <dd class="mt-1 text-sm font-medium text-zinc-900">
                {{ $withdrawal->customer_email }}
            </dd>
        </div>

        @if ($withdrawal->reason_text)
            <div class="px-6 py-4 sm:col-span-2 sm:border-t sm:border-zinc-100">
                <dt class="text-xs font-medium uppercase tracking-wide text-zinc-500">
                    @lang('shop::app.eu_withdrawal.confirmation.reason')
                </dt>

                <dd class="mt-1 whitespace-pre-wrap text-sm text-zinc-900">{{ $withdrawal->reason_text }}</dd>
            </div>
        @endif
    </dl>
</div>

{{-- Refund Timeline --}}
<div class="mt-6 rounded-xl border border-zinc-200 bg-white p-5">
    <h3 class="text-base font-medium text-zinc-900">
        @lang('shop::app.eu_withdrawal.confirmation.next_steps_title')
    </h3>

    <ol class="mt-4 space-y-3 text-sm">
        <li class="flex gap-3">
            <span class="grid h-6 w-6 flex-shrink-0 place-items-center rounded-full bg-emerald-100 text-emerald-700 icon-check-box"></span>

            <div>
                <p class="font-medium text-zinc-900">
                    @lang('shop::app.eu_withdrawal.confirmation.step_received')
                </p>

                <p class="text-xs text-zinc-500">
                    {{ $withdrawal->received_at->copy()->setTimezone('UTC')->format('d M Y, H:i') }} UTC
                </p>
            </div>
        </li>

        <li class="flex gap-3">
            @if ($withdrawal->confirmation_sent_at)
                <span class="grid h-6 w-6 flex-shrink-0 place-items-center rounded-full bg-emerald-100 text-emerald-700 icon-check-box"></span>
            @else
                <span class="grid h-6 w-6 flex-shrink-0 place-items-center rounded-full border border-zinc-300 text-zinc-400 icon-radio-unselect"></span>
            @endif

            <div>
                <p class="font-medium text-zinc-900">
                    @lang('shop::app.eu_withdrawal.confirmation.step_email')
                </p>

                <p class="text-xs text-zinc-500">
                    @if ($withdrawal->confirmation_sent_at)
                        @lang('shop::app.eu_withdrawal.confirmation.email_sent')
                    @elseif ($withdrawal->confirmation_error)
                        <span class="text-amber-700">@lang('shop::app.eu_withdrawal.confirmation.email_pending')</span>
                    @else
                        @lang('shop::app.eu_withdrawal.confirmation.email_pending')
                    @endif
                </p>
            </div>
        </li>
        
        <li class="flex gap-3">
            @if ($withdrawal->refunded_at)
                <span class="grid h-6 w-6 flex-shrink-0 place-items-center rounded-full bg-emerald-100 text-emerald-700 icon-check-box"></span>

                <div>
                    <p class="font-medium text-zinc-900">
                        @lang('shop::app.eu_withdrawal.confirmation.step_refund_done')
                    </p>

                    <p class="text-xs text-zinc-500">
                        {{ $withdrawal->refunded_at->copy()->setTimezone('UTC')->format('d M Y, H:i') }} UTC
                    </p>
                </div>
            @elseif ($withdrawal->declined_at)
                <span class="grid h-6 w-6 flex-shrink-0 place-items-center rounded-full bg-red-100 text-red-700">
                    <span class="text-base font-bold leading-none">&times;</span>
                </span>

                <div class="flex-1">
                    <p class="font-medium text-zinc-900">
                        @lang('shop::app.eu_withdrawal.confirmation.step_declined')
                    </p>

                    @if ($withdrawal->declined_reason)
                        <div class="mt-2 rounded-md border border-red-100 bg-red-50 p-3">
                            <p class="text-xs font-medium uppercase tracking-wide text-red-700">
                                @lang('shop::app.eu_withdrawal.confirmation.declined_reason_label')
                            </p>

                            <p class="mt-1 whitespace-pre-wrap text-sm text-red-900">{{ $withdrawal->declined_reason }}</p>
                        </div>
                    @endif

                    <p class="mt-2 text-xs text-zinc-500">
                        @lang('shop::app.eu_withdrawal.confirmation.declined_notice')
                    </p>
                </div>
            @else
                <span class="grid h-6 w-6 flex-shrink-0 place-items-center rounded-full border border-zinc-300 text-zinc-400 icon-radio-unselect"></span>

                <div>
                    <p class="font-medium text-zinc-900">
                        @lang('shop::app.eu_withdrawal.confirmation.step_refund')
                    </p>

                    <p class="text-xs text-zinc-500">
                        @lang('shop::app.eu_withdrawal.confirmation.refund_notice')
                    </p>
                </div>
            @endif
        </li>
    </ol>
</div>

<p class="mt-6 text-xs text-zinc-500">
    @lang('shop::app.eu_withdrawal.confirmation.durable_medium_notice')
</p>

@if (! $isGuest)
    <div class="mt-6">
        <a
            href="{{ route('shop.customers.account.orders.view', $withdrawal->order_id) }}"
            class="inline-flex items-center gap-1 text-sm text-navyBlue hover:underline"
        >
            <span class="icon-arrow-left rtl:icon-arrow-right"></span>

            @lang('shop::app.customers.account.orders.title')
        </a>
    </div>
@endif
