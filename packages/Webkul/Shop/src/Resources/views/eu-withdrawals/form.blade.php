<x-shop::layouts>
    <x-slot:title>
        @lang('shop::app.eu_withdrawal.form.page_title')
    </x-slot>

    <div class="container mt-10 mx-auto max-w-3xl px-5 max-md:mt-6 max-md:px-4">
        <h1 class="text-2xl font-medium max-md:text-xl">
            @lang('shop::app.eu_withdrawal.form.heading')
        </h1>

        {{-- Statutory Effect Notice --}}
        <div class="mt-4 flex items-start gap-3 rounded-xl border border-amber-200 bg-amber-50 p-4 text-sm text-amber-900">
            <span class="icon-warning mt-0.5 text-xl leading-none"></span>

            <div>
                <p class="font-medium">
                    @lang('shop::app.eu_withdrawal.form.legal_notice_title')
                </p>

                <p class="mt-1 text-amber-800">
                    @lang('shop::app.eu_withdrawal.form.legal_effect', [
                        'order_id' => $order->increment_id ?? $order->id,
                    ])
                </p>
            </div>
        </div>

        <form
            method="POST"
            action="{{ $formUrl }}"
            class="mt-6"
        >
            @csrf

            <div class="grid grid-cols-1 gap-6 md:grid-cols-3">
                {{-- Order Summary --}}
                <aside class="rounded-xl border border-zinc-200 bg-white p-5">
                    <p class="text-xs font-medium uppercase tracking-wide text-zinc-500">
                        @lang('shop::app.eu_withdrawal.form.order_summary')
                    </p>

                    <p class="mt-2 text-lg font-semibold text-zinc-900">
                        #{{ $order->increment_id ?? $order->id }}
                    </p>

                    <p class="mt-1 text-sm text-zinc-600">
                        @lang('shop::app.eu_withdrawal.form.placed_on',
                            ['date' => core()->formatDate($order->created_at, 'd M Y')])
                    </p>

                    <div class="mt-4 grid gap-2 border-t border-zinc-100 pt-4 text-sm">
                        <div class="flex items-center justify-between text-zinc-600">
                            <span>@lang('shop::app.eu_withdrawal.form.order_total')</span>

                            <span class="font-medium text-zinc-900">{{ core()->formatPrice($order->grand_total, $order->order_currency_code) }}</span>
                        </div>

                        <div class="flex items-center justify-between text-zinc-600">
                            <span>@lang('shop::app.eu_withdrawal.form.order_items')</span>

                            <span class="font-medium text-zinc-900">{{ $order->total_qty_ordered }}</span>
                        </div>
                    </div>
                </aside>

                {{-- Reason Input --}}
                <div class="rounded-xl border border-zinc-200 bg-white p-5 md:col-span-2">
                    <label
                        for="reason_text"
                        class="block text-base font-medium text-zinc-900"
                    >
                        @lang('shop::app.eu_withdrawal.form.reason_label')

                        <span class="ml-1 text-xs font-normal text-zinc-500">
                            @lang('shop::app.eu_withdrawal.form.reason_optional')
                        </span>
                    </label>

                    <p class="mt-1 text-xs text-zinc-500">
                        @lang('shop::app.eu_withdrawal.form.reason_help')
                    </p>

                    <textarea
                        id="reason_text"
                        name="reason_text"
                        rows="6"
                        maxlength="5000"
                        class="mt-3 block w-full rounded-lg border border-zinc-200 px-4 py-3 text-sm focus:border-navyBlue focus:outline-none focus:ring-1 focus:ring-navyBlue"
                        placeholder="@lang('shop::app.eu_withdrawal.form.reason_placeholder')"
                    >{{ old('reason_text') }}</textarea>

                    @error('reason_text')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Action Footer — Spans the full form width, not tucked inside the reason card. --}}
            <div class="mt-6 flex items-center justify-end gap-3">
                <a
                    href="{{ route('shop.home.index') }}"
                    class="secondary-button border-zinc-200 px-5 py-3 font-normal"
                >
                    @lang('shop::app.eu_withdrawal.form.cancel')
                </a>

                <button
                    type="submit"
                    class="primary-button px-6 py-3"
                >
                    @lang('shop::app.eu_withdrawal.form.submit')
                </button>
            </div>
        </form>
    </div>
</x-shop::layouts>
