@php
    use Webkul\EUWithdrawal\Enums\WithdrawalStatus;

    $statusBadge = [
        WithdrawalStatus::RECEIVED => 'bg-amber-100 text-amber-800 dark:bg-amber-900/40 dark:text-amber-200',
        WithdrawalStatus::REFUNDED => 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/40 dark:text-emerald-200',
        WithdrawalStatus::DECLINED => 'bg-red-100 text-red-800 dark:bg-red-900/40 dark:text-red-200',
    ][$withdrawal->status] ?? 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200';

    $timelineDot = [
        'done'    => '<span class="mt-0.5 grid h-7 w-7 flex-shrink-0 place-items-center rounded-full bg-emerald-500 text-white shadow-sm ring-4 ring-emerald-100 dark:bg-emerald-500 dark:ring-emerald-900/40"><svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M16.704 5.292a1 1 0 010 1.414l-7.999 8a1 1 0 01-1.413 0l-4-3.999a1 1 0 011.413-1.415L8 12.585l7.295-7.293a1 1 0 011.41 0z" clip-rule="evenodd"/></svg></span>',

        'declined' => '<span class="mt-0.5 grid h-7 w-7 flex-shrink-0 place-items-center rounded-full bg-red-500 text-white shadow-sm ring-4 ring-red-100 dark:bg-red-500 dark:ring-red-900/40"><svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/></svg></span>',

        'warning' => '<span class="mt-0.5 grid h-7 w-7 flex-shrink-0 place-items-center rounded-full bg-amber-500 text-white shadow-sm ring-4 ring-amber-100 dark:bg-amber-500 dark:ring-amber-900/40"><svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M8.485 2.495c.673-1.167 2.357-1.167 3.03 0l6.28 10.875c.673 1.167-.17 2.625-1.516 2.625H3.72c-1.347 0-2.189-1.458-1.515-2.625L8.485 2.495zM10 5a.75.75 0 01.75.75v3.5a.75.75 0 01-1.5 0v-3.5A.75.75 0 0110 5zm0 9a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd"/></svg></span>',

        'pending' => '<span class="mt-0.5 grid h-7 w-7 flex-shrink-0 place-items-center rounded-full border-2 border-dashed border-gray-300 bg-white ring-4 ring-gray-100 dark:border-gray-600 dark:bg-gray-900 dark:ring-gray-800"></span>',
    ];
@endphp

<x-admin::layouts>
    <x-slot:title>
        @lang('admin::app.eu_withdrawal.view.title', ['uuid' => $withdrawal->uuid])
    </x-slot>

    {{-- Header --}}
    <div class="flex items-center justify-between gap-4 max-sm:flex-wrap">
        <div class="flex items-center gap-2.5">
            <p class="py-3 text-xl font-bold leading-6 text-gray-800 dark:text-white">
                @lang('admin::app.eu_withdrawal.view.heading')

                <span class="ml-1 font-mono text-base text-gray-500 dark:text-gray-400">
                    #{{ $withdrawal->id }}
                </span>
            </p>

            <span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-medium {{ $statusBadge }}">
                @lang('admin::app.eu_withdrawal.status.'.$withdrawal->status)
            </span>

            @if ($withdrawal->is_guest)
                <span class="inline-flex items-center rounded-full bg-gray-100 px-3 py-1 text-xs font-medium text-gray-700 dark:bg-gray-800 dark:text-gray-300">
                    @lang('admin::app.eu_withdrawal.view.guest_badge')
                </span>
            @endif
        </div>

        <a
            href="{{ route('admin.sales.eu-withdrawals.index') }}"
            class="transparent-button hover:bg-gray-200 dark:text-white dark:hover:bg-gray-800"
        >
            @lang('admin::app.eu_withdrawal.view.back')
        </a>
    </div>

    {{-- Body --}}
    <div class="mt-3.5 flex gap-2.5 max-xl:flex-wrap">
        {{-- Left column --}}
        <div class="flex flex-1 flex-col gap-2.5 max-xl:flex-auto">
            {{-- Evidence card --}}
            <div class="box-shadow rounded bg-white dark:bg-gray-900">
                <div class="flex items-center justify-between border-b border-slate-300 p-4 dark:border-gray-800">
                    <p class="text-base font-semibold text-gray-800 dark:text-white">
                        @lang('admin::app.eu_withdrawal.view.evidence')
                    </p>

                    <p class="text-xs text-gray-500 dark:text-gray-400">
                        @lang('admin::app.eu_withdrawal.view.evidence_note')
                    </p>
                </div>

                <div class="grid grid-cols-1 divide-y divide-slate-200 dark:divide-gray-800 sm:grid-cols-2 sm:divide-x sm:divide-y-0">
                    <div class="px-4 py-4">
                        <p class="text-xs font-medium uppercase tracking-wide text-gray-500 dark:text-gray-400">
                            @lang('admin::app.eu_withdrawal.view.received_at')
                        </p>

                        <p class="mt-1 text-sm font-medium text-gray-800 dark:text-white">
                            {{ $withdrawal->received_at->format('d M Y, H:i T') }}
                        </p>
                    </div>
                    
                    <div class="px-4 py-4">
                        <p class="text-xs font-medium uppercase tracking-wide text-gray-500 dark:text-gray-400">
                            @lang('admin::app.eu_withdrawal.view.uuid')
                        </p>

                        <div class="mt-1 flex items-center gap-2 rounded-lg border border-slate-200 bg-gray-50 px-3 py-2 dark:border-gray-800 dark:bg-gray-950">
                            <code class="flex-1 break-all font-mono text-sm text-gray-800 dark:text-gray-200">
                                {{ $withdrawal->uuid }}
                            </code>

                            <button
                                type="button"
                                title="@lang('admin::app.eu_withdrawal.view.copy_reference')"
                                data-eu-clipboard="{{ $withdrawal->uuid }}"
                                data-eu-clipboard-message="@lang('admin::app.eu_withdrawal.view.reference_copied')"
                                class="grid h-7 w-7 flex-shrink-0 place-items-center rounded-md text-gray-500 transition-all hover:bg-gray-200 hover:text-gray-800 dark:text-gray-400 dark:hover:bg-gray-800 dark:hover:text-gray-100"
                            >
                                <span class="icon-copy text-lg"></span>
                            </button>
                        </div>
                    </div>

                    <div class="px-4 py-4">
                        <p class="text-xs font-medium uppercase tracking-wide text-gray-500 dark:text-gray-400">
                            @lang('admin::app.eu_withdrawal.view.order')
                        </p>

                        <p class="mt-1 text-sm font-medium">
                            <a
                                class="text-blue-600 hover:underline dark:text-blue-400"
                                href="{{ route('admin.sales.orders.view', $withdrawal->order_id) }}"
                            >
                                #{{ optional($withdrawal->order)->increment_id ?? $withdrawal->order_id }}
                            </a>
                        </p>
                    </div>

                    <div class="px-4 py-4">
                        <p class="text-xs font-medium uppercase tracking-wide text-gray-500 dark:text-gray-400">
                            @lang('admin::app.eu_withdrawal.view.customer_email')
                        </p>

                        <p class="mt-1 break-all text-sm font-medium text-gray-800 dark:text-white">
                            {{ $withdrawal->customer_email }}
                        </p>
                    </div>

                    <div class="px-4 py-4">
                        <p class="text-xs font-medium uppercase tracking-wide text-gray-500 dark:text-gray-400">
                            @lang('admin::app.eu_withdrawal.view.channel')
                        </p>

                        <p class="mt-1 text-sm font-medium text-gray-800 dark:text-white">
                            {{ optional($withdrawal->channel)->code ?? '—' }}
                        </p>
                    </div>

                    <div class="px-4 py-4">
                        <p class="text-xs font-medium uppercase tracking-wide text-gray-500 dark:text-gray-400">
                            @lang('admin::app.eu_withdrawal.view.locale')
                        </p>

                        <p class="mt-1 text-sm font-medium text-gray-800 dark:text-white">
                            {{ $withdrawal->locale }}
                        </p>
                    </div>
                </div>

                @if ($withdrawal->reason_text)
                    <div class="border-t border-slate-200 px-4 py-4 dark:border-gray-800">
                        <p class="text-xs font-medium uppercase tracking-wide text-gray-500 dark:text-gray-400">
                            @lang('admin::app.eu_withdrawal.view.reason')
                        </p>

                        <p class="mt-1 whitespace-pre-wrap text-sm text-gray-800 dark:text-white">{{ $withdrawal->reason_text }}</p>
                    </div>
                @endif
            </div>

            {{-- Timeline card --}}
            <div class="box-shadow rounded bg-white dark:bg-gray-900">
                <div class="flex justify-between p-4">
                    <p class="text-base font-semibold text-gray-800 dark:text-white">
                        @lang('admin::app.eu_withdrawal.view.timeline')
                    </p>
                </div>

                <ol class="grid gap-0">
                    <li class="flex gap-3 border-t border-slate-200 px-4 py-3 dark:border-gray-800">
                        {!! $timelineDot['done'] !!}

                        <div class="flex-1">
                            <p class="text-sm font-medium text-gray-800 dark:text-white">
                                @lang('admin::app.eu_withdrawal.view.timeline_received')
                            </p>

                            <p class="mt-0.5 text-xs text-gray-600 dark:text-gray-400">
                                @lang('admin::app.eu_withdrawal.view.timeline_received_desc')
                            </p>

                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-500">
                                {{ $withdrawal->received_at->format('d M Y, H:i T') }}
                            </p>
                        </div>
                    </li>

                    <li class="flex gap-3 border-t border-slate-200 px-4 py-3 dark:border-gray-800">
                        @if ($withdrawal->confirmation_sent_at)
                            {!! $timelineDot['done'] !!}
                        @elseif ($withdrawal->confirmation_error)
                            {!! $timelineDot['warning'] !!}
                        @else
                            {!! $timelineDot['pending'] !!}
                        @endif

                        <div class="flex-1">
                            <p class="text-sm font-medium text-gray-800 dark:text-white">
                                @lang('admin::app.eu_withdrawal.view.timeline_initial_email')
                            </p>

                            @if ($withdrawal->confirmation_sent_at)
                                <p class="mt-0.5 text-xs text-gray-600 dark:text-gray-400">
                                    @lang('admin::app.eu_withdrawal.view.timeline_initial_email_desc_sent')
                                </p>

                                <p class="mt-1 text-xs text-gray-500 dark:text-gray-500">
                                    {{ $withdrawal->confirmation_sent_at->format('d M Y, H:i T') }}
                                </p>
                            @elseif ($withdrawal->confirmation_error)
                                <p class="mt-0.5 text-xs text-gray-600 dark:text-gray-400">
                                    @lang('admin::app.eu_withdrawal.view.timeline_initial_email_desc_error')
                                </p>

                                <p class="mt-1 text-xs text-amber-700 dark:text-amber-300">
                                    {{ $withdrawal->confirmation_error }}
                                </p>
                            @else
                                <p class="mt-0.5 text-xs text-gray-600 dark:text-gray-400">
                                    @lang('admin::app.eu_withdrawal.view.timeline_initial_email_desc_pending')
                                </p>
                            @endif
                        </div>
                    </li>

                    <li class="flex gap-3 border-t border-slate-200 px-4 py-3 dark:border-gray-800">
                        @if ($withdrawal->declined_at)
                            {!! $timelineDot['declined'] !!}

                            <div class="flex-1">
                                <p class="text-sm font-medium text-gray-800 dark:text-white">
                                    @lang('admin::app.eu_withdrawal.view.timeline_declined')
                                </p>

                                <p class="mt-0.5 text-xs text-gray-600 dark:text-gray-400">
                                    @if ($withdrawal->declinedBy)
                                        @lang('admin::app.eu_withdrawal.view.timeline_declined_desc', ['name' => $withdrawal->declinedBy->name])
                                    @else
                                        @lang('admin::app.eu_withdrawal.view.timeline_declined_desc_system')
                                    @endif
                                </p>

                                <p class="mt-1 text-xs text-gray-500 dark:text-gray-500">
                                    {{ $withdrawal->declined_at->format('d M Y, H:i T') }}
                                </p>

                                @if ($withdrawal->declined_reason)
                                    <div class="mt-2 rounded-md border border-red-100 bg-red-50 p-2.5 dark:border-red-900/40 dark:bg-red-900/20">
                                        <p class="text-xs font-medium uppercase tracking-wide text-red-700 dark:text-red-300">
                                            @lang('admin::app.eu_withdrawal.view.timeline_declined_reason_label')
                                        </p>

                                        <p class="mt-1 text-sm text-red-900 dark:text-red-200">{{ $withdrawal->declined_reason }}</p>
                                    </div>
                                @endif
                            </div>
                        @elseif ($withdrawal->refunded_at)
                            {!! $timelineDot['done'] !!}

                            <div class="flex-1">
                                <p class="text-sm font-medium text-gray-800 dark:text-white">
                                    @lang('admin::app.eu_withdrawal.view.timeline_refunded')
                                </p>

                                <p class="mt-0.5 text-xs text-gray-600 dark:text-gray-400">
                                    @if ($withdrawal->refundedBy)
                                        @lang('admin::app.eu_withdrawal.view.timeline_refunded_desc', ['name' => $withdrawal->refundedBy->name])
                                    @else
                                        @lang('admin::app.eu_withdrawal.view.timeline_refunded_desc_system')
                                    @endif
                                </p>

                                <p class="mt-1 text-xs text-gray-500 dark:text-gray-500">
                                    {{ $withdrawal->refunded_at->format('d M Y, H:i T') }}
                                </p>

                                @if ($withdrawal->refund_note)
                                    <div class="mt-2 rounded-md border border-emerald-100 bg-emerald-50 p-2.5 dark:border-emerald-900/40 dark:bg-emerald-900/20">
                                        <p class="text-xs font-medium uppercase tracking-wide text-emerald-700 dark:text-emerald-300">
                                            @lang('admin::app.eu_withdrawal.view.timeline_refunded_note_label')
                                        </p>

                                        <p class="mt-1 text-sm text-emerald-900 dark:text-emerald-200">{{ $withdrawal->refund_note }}</p>
                                    </div>
                                @endif
                            </div>
                        @else
                            {!! $timelineDot['pending'] !!}

                            <div class="flex-1">
                                <p class="text-sm font-medium text-gray-800 dark:text-white">
                                    @lang('admin::app.eu_withdrawal.view.timeline_resolution')
                                </p>

                                <p class="mt-0.5 text-xs text-gray-600 dark:text-gray-400">
                                    @lang('admin::app.eu_withdrawal.view.timeline_resolution_desc')
                                </p>
                            </div>
                        @endif
                    </li>

                    @if ($withdrawal->declined_at || $withdrawal->refunded_at)
                        <li class="flex gap-3 border-t border-slate-200 px-4 py-3 dark:border-gray-800">
                            @if ($withdrawal->final_confirmation_sent_at)
                                {!! $timelineDot['done'] !!}
                            @else
                                {!! $timelineDot['pending'] !!}
                            @endif

                            <div class="flex-1">
                                <p class="text-sm font-medium text-gray-800 dark:text-white">
                                    @lang('admin::app.eu_withdrawal.view.timeline_final_email')
                                </p>

                                @if ($withdrawal->final_confirmation_sent_at)
                                    <p class="mt-0.5 text-xs text-gray-600 dark:text-gray-400">
                                        @lang('admin::app.eu_withdrawal.view.timeline_final_email_desc_sent')
                                    </p>

                                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-500">
                                        {{ $withdrawal->final_confirmation_sent_at->format('d M Y, H:i T') }}
                                    </p>
                                @else
                                    <p class="mt-0.5 text-xs text-gray-600 dark:text-gray-400">
                                        @lang('admin::app.eu_withdrawal.view.timeline_final_email_desc_pending')
                                    </p>
                                @endif
                            </div>
                        </li>
                    @endif
                </ol>
            </div>
        </div>

        {{-- Right column: actions --}}
        <div class="flex w-full max-w-[360px] flex-col gap-2.5 max-xl:max-w-full">
            <div class="box-shadow rounded bg-white dark:bg-gray-900">
                <div class="flex justify-between p-4">
                    <p class="text-base font-semibold text-gray-800 dark:text-white">
                        @lang('admin::app.eu_withdrawal.view.actions')
                    </p>
                </div>

                <div class="border-t border-slate-200 p-4 dark:border-gray-800">
                    <p class="text-xs leading-relaxed text-gray-500 dark:text-gray-400">
                        @lang('admin::app.eu_withdrawal.view.actions_note')
                    </p>
                </div>

                @php
                    $isTerminalStatus = in_array($withdrawal->status, [WithdrawalStatus::REFUNDED, WithdrawalStatus::DECLINED], true);

                    $confirmationButtonLabel = $isTerminalStatus
                        ? 'admin::app.eu_withdrawal.view.send_final_confirmation'
                        : 'admin::app.eu_withdrawal.view.resend_confirmation';

                    $confirmationModalMessage = $isTerminalStatus
                        ? 'admin::app.eu_withdrawal.view.send_final_confirmation_confirm_msg'
                        : 'admin::app.eu_withdrawal.view.resend_confirmation_confirm_msg';
                @endphp

                @if (bouncer()->hasPermission('sales.eu_withdrawals.resend_confirmation'))
                    <form
                        method="POST"
                        action="{{ route('admin.sales.eu-withdrawals.resend_confirmation', $withdrawal->id) }}"
                        ref="resendConfirmationForm"
                        class="border-t border-slate-200 p-4 dark:border-gray-800"
                    >
                        @csrf

                        <button
                            type="button"
                            class="secondary-button w-full"
                            @click="$emitter.emit('open-confirm-modal', {
                                message: '{{ trans($confirmationModalMessage) }}',
                                agree: () => this.$refs['resendConfirmationForm'].submit(),
                            })"
                        >
                            @lang($confirmationButtonLabel)
                        </button>
                    </form>
                @endif

                @if (bouncer()->hasPermission('sales.eu_withdrawals.mark_refunded'))
                    <form
                        method="POST"
                        action="{{ route('admin.sales.eu-withdrawals.mark_refunded', $withdrawal->id) }}"
                        ref="markRefundedForm"
                        class="grid gap-2 border-t border-slate-200 p-4 dark:border-gray-800"
                    >
                        @csrf

                        <label class="text-sm font-medium text-gray-700 dark:text-gray-200">
                            @lang('admin::app.eu_withdrawal.view.refund_note_label')
                        </label>

                        <input
                            type="text"
                            name="refund_note"
                            maxlength="500"
                            placeholder="@lang('admin::app.eu_withdrawal.view.refund_note_placeholder')"
                            class="w-full rounded-lg border bg-white px-3 py-2 text-sm text-gray-700 transition-all hover:border-gray-400 focus:border-gray-400 dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300"
                        >

                        <button
                            type="button"
                            @class([
                                'w-full',
                                'primary-button' => $withdrawal->status !== WithdrawalStatus::REFUNDED,
                                'secondary-button' => $withdrawal->status === WithdrawalStatus::REFUNDED,
                            ])
                            @click="$emitter.emit('open-confirm-modal', {
                                message: '@lang('admin::app.eu_withdrawal.view.mark_refunded_confirm_msg')',
                                agree: () => this.$refs['markRefundedForm'].submit(),
                            })"
                        >
                            @lang('admin::app.eu_withdrawal.view.mark_refunded')
                        </button>
                    </form>
                @endif

                @if (bouncer()->hasPermission('sales.eu_withdrawals.decline'))
                    <form
                        method="POST"
                        action="{{ route('admin.sales.eu-withdrawals.decline', $withdrawal->id) }}"
                        ref="declineForm"
                        class="grid gap-2 border-t border-slate-200 p-4 dark:border-gray-800"
                    >
                        @csrf

                        <label class="text-sm font-medium text-gray-700 dark:text-gray-200">
                            @lang('admin::app.eu_withdrawal.view.decline_reason_label')
                        </label>

                        <input
                            type="text"
                            name="declined_reason"
                            ref="declineReasonInput"
                            maxlength="500"
                            required
                            placeholder="@lang('admin::app.eu_withdrawal.view.decline_reason_placeholder')"
                            class="w-full rounded-lg border bg-white px-3 py-2 text-sm text-gray-700 transition-all hover:border-gray-400 focus:border-gray-400 dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300"
                        >

                        <button
                            type="button"
                            class="secondary-button w-full !border-red-200 !text-red-700 hover:!bg-red-50 dark:!text-red-300 dark:hover:!bg-red-900/30"
                            @click="
                                if (! this.$refs['declineReasonInput'].value.trim()) {
                                    this.$refs['declineReasonInput'].focus();
                                    return;
                                }
                                $emitter.emit('open-confirm-modal', {
                                    message: '@lang('admin::app.eu_withdrawal.view.decline_confirm_msg')',
                                    agree: () => this.$refs['declineForm'].submit(),
                                });
                            "
                        >
                            @lang('admin::app.eu_withdrawal.view.decline')
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </div>

    @pushOnce('scripts')
        <script>
            document.addEventListener('click', async function (event) {
                const button = event.target.closest('[data-eu-clipboard]');

                if (! button) {
                    return;
                }

                event.preventDefault();

                const text = button.getAttribute('data-eu-clipboard');
                const message = button.getAttribute('data-eu-clipboard-message') || 'Copied';

                try {
                    if (navigator.clipboard && window.isSecureContext) {
                        await navigator.clipboard.writeText(text);
                    } else {
                        const ta = document.createElement('textarea');
                        ta.value = text;
                        ta.style.position = 'fixed';
                        ta.style.opacity = '0';
                        document.body.appendChild(ta);
                        ta.select();
                        document.execCommand('copy');
                        document.body.removeChild(ta);
                    }

                    const emitter = window?.app?.config?.globalProperties?.$emitter;

                    if (emitter) {
                        emitter.emit('add-flash', { type: 'success', message: message });
                    }
                } catch (err) {
                    console.error('Copy failed:', err);
                }
            });
        </script>
    @endPushOnce
</x-admin::layouts>
