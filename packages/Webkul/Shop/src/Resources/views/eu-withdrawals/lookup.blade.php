<x-shop::layouts>
    <x-slot:title>
        @lang('shop::app.eu_withdrawal.lookup.page_title')
    </x-slot>

    <div class="container mt-10 mx-auto max-w-xl px-5 max-md:mt-6 max-md:px-4">
        <div class="rounded-xl border border-zinc-200 bg-white p-8 max-sm:p-5">
            <h1 class="text-2xl font-medium max-md:text-xl">
                @lang('shop::app.eu_withdrawal.lookup.heading')
            </h1>

            <p class="mt-2 text-sm text-zinc-600">
                @lang('shop::app.eu_withdrawal.lookup.intro')
            </p>

            @if (session('lookup_sent'))
                <div class="mt-6 flex items-start gap-3 rounded-xl border border-emerald-200 bg-emerald-50 p-4 text-sm text-emerald-800">
                    <span class="icon-check-box mt-0.5 text-xl leading-none"></span>

                    <p>@lang('shop::app.eu_withdrawal.lookup.sent_notice')</p>
                </div>
            @endif

            <form
                method="POST"
                action="{{ route('shop.eu-withdrawal.guest.lookup.submit') }}"
                class="mt-6 space-y-5"
            >
                @csrf

                <div>
                    <label
                        for="order_increment_id"
                        class="mb-1.5 block text-sm font-medium text-zinc-900"
                    >
                        @lang('shop::app.eu_withdrawal.lookup.order_number')
                    </label>

                    <input
                        id="order_increment_id"
                        name="order_increment_id"
                        type="text"
                        required
                        autocomplete="off"
                        value="{{ old('order_increment_id') }}"
                        class="block w-full rounded-lg border border-zinc-200 px-4 py-3 text-sm focus:border-navyBlue focus:outline-none focus:ring-1 focus:ring-navyBlue"
                    >

                    @error('order_increment_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label
                        for="email"
                        class="mb-1.5 block text-sm font-medium text-zinc-900"
                    >
                        @lang('shop::app.eu_withdrawal.lookup.email')
                    </label>

                    <input
                        id="email"
                        name="email"
                        type="email"
                        required
                        autocomplete="email"
                        value="{{ old('email') }}"
                        class="block w-full rounded-lg border border-zinc-200 px-4 py-3 text-sm focus:border-navyBlue focus:outline-none focus:ring-1 focus:ring-navyBlue"
                    >
                    
                    @error('email')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <button
                    type="submit"
                    class="primary-button w-full py-3 text-base"
                >
                    @lang('shop::app.eu_withdrawal.lookup.submit')
                </button>
            </form>
        </div>

        <p class="mt-6 text-center text-xs text-zinc-500">
            @lang('shop::app.eu_withdrawal.lookup.legal_note')
        </p>
    </div>
</x-shop::layouts>
