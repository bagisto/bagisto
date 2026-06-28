<x-shop::layouts>
    <x-slot:title>
        {{ trans('marketplace::app.seller.connect.title') }}
    </x-slot>

    <div class="container mx-auto px-4 py-8 max-w-3xl">
        <h1 class="text-2xl font-bold mb-2">{{ trans('marketplace::app.seller.connect.title') }}</h1>
        <p class="text-gray-500 mb-6">{{ trans('marketplace::app.seller.connect.subtitle') }}</p>

        @if (session('info'))
            <div class="mb-4 rounded-lg bg-blue-50 text-blue-700 px-4 py-3">{{ session('info') }}</div>
        @endif
        @if (session('error'))
            <div class="mb-4 rounded-lg bg-red-50 text-red-700 px-4 py-3">{{ session('error') }}</div>
        @endif

        @if (! $configured)
            <div class="rounded-lg border border-amber-200 bg-amber-50 px-5 py-4 text-amber-800">
                {{ trans('marketplace::app.seller.connect.not-configured') }}
            </div>
        @else
            <div class="rounded-xl border border-gray-200 p-6">
                <div class="flex items-center justify-between mb-4">
                    <span class="font-semibold">{{ trans('marketplace::app.seller.connect.status') }}</span>
                    @if ($onboarded)
                        <span class="inline-flex items-center rounded-full bg-green-100 text-green-700 px-3 py-1 text-sm font-medium">
                            {{ trans('marketplace::app.seller.connect.connected') }}
                        </span>
                    @elseif ($seller->stripe_account_id)
                        <span class="inline-flex items-center rounded-full bg-amber-100 text-amber-700 px-3 py-1 text-sm font-medium">
                            {{ trans('marketplace::app.seller.connect.incomplete') }}
                        </span>
                    @else
                        <span class="inline-flex items-center rounded-full bg-gray-100 text-gray-600 px-3 py-1 text-sm font-medium">
                            {{ trans('marketplace::app.seller.connect.not-connected') }}
                        </span>
                    @endif
                </div>

                <ul class="text-sm text-gray-600 space-y-1 mb-6">
                    <li>• {{ trans('marketplace::app.seller.connect.charges') }}:
                        <strong>{{ $seller->stripe_charges_enabled ? '✓' : '—' }}</strong></li>
                    <li>• {{ trans('marketplace::app.seller.connect.payouts') }}:
                        <strong>{{ $seller->stripe_payouts_enabled ? '✓' : '—' }}</strong></li>
                </ul>

                @if (! $onboarded)
                    <form method="POST" action="{{ route('marketplace.connect.onboard') }}">
                        @csrf
                        <button type="submit"
                            class="inline-flex items-center rounded-lg bg-[#635bff] px-5 py-2.5 text-white font-semibold hover:opacity-90">
                            {{ $seller->stripe_account_id
                                ? trans('marketplace::app.seller.connect.continue-onboarding')
                                : trans('marketplace::app.seller.connect.connect-btn') }}
                        </button>
                    </form>
                @else
                    <p class="text-green-700 text-sm">{{ trans('marketplace::app.seller.connect.ready') }}</p>
                @endif
            </div>
        @endif
    </div>
</x-shop::layouts>
